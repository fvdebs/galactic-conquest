<?php

declare(strict_types=1);

namespace GC\Combat\Format;

use DateTimeImmutable;
use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

use function json_encode;
use function array_merge;
use function array_key_exists;
use function array_filter;
use function ksort;
use function in_array;
use function is_numeric;
use function is_array;

final class JsonFormatter implements JsonFormatterInterface
{
    public const KEY_REPORT = 'report';
    public const KEY_DATA = 'data';
    public const KEY_TARGET = 'target';
    public const KEY_DEFENSE = 'defense';
    public const KEY_OFFENSE = 'offense';
    public const KEY_SUMMARY = 'summary';
    public const KEY_RESOURCE = 'resource';
    public const KEY_SALVAGED = 'salvaged';
    public const KEY_EXTRACTOR = 'extractor';
    public const KEY_PROTECTED = 'protected';
    public const KEY_STEAL_CAPACITY = 'stealCapacity';
    public const KEY_EXTRACTOR_LOST = 'lost';
    public const KEY_EXTRACTOR_STOLEN = 'stolen';
    public const KEY_METAL = 'metal';
    public const KEY_CRYSTAL = 'crystal';
    public const KEY_CARRIER = 'carrier';
    public const KEY_CARRIER_CAPACITY = 'capacity';
    public const KEY_CARRIER_CAPACITY_CONSUMED = 'consumed';
    public const KEY_CARRIER_LOSSES = 'losses';
    public const KEY_BEFORE = 'before';
    public const KEY_AFTER = 'after';
    public const KEY_DESTROYED = 'destroyed';
    public const KEY_FLEET_ID = 'fleetId';
    public const KEY_CREATED_AT = 'createdAt';
    public const KEY_SETTINGS = 'settings';
    public const KEY_COMBAT_TICKS = 'combatTicks';
    public const KEY_IS_PRE_FIRE_TICK = 'isPreFireTick';
    public const KEY_IS_LAST_TICK = 'isLastTick';
    public const KEY_EXTRACTOR_STEAL_RATIO = 'extractorStealRatio';
    public const KEY_TARGET_SALVAGE_RATIO = 'targetSalvageRatio';
    public const KEY_DEFENDER_SALVAGE_RATIO = 'defenderSalvageRatio';
    public const KEY_UNIT_ID = 'unitId';
    public const KEY_NAME = 'name';
    public const KEY_GROUP = 'group';
    public const KEY_EXTRACTOR_PROTECTION_AMOUNT = 'extractorProtectionAmount';
    public const KEY_EXTRACTOR_STEAL_AMOUNT = 'extractorStealAmount';
    public const KEY_CARRIER_CAPACITY_RATIO = 'carrierCapacityRatio';
    public const KEY_CARRIER_CAPACITY_CONSUMED_RATIO = 'carrierCapacityConsumedRatio';
    public const KEY_ATTACK_POWER = 'attackpower';
    public const KEY_DISTRIBUTION_RATIO = 'distributionRatio';
    public const KEY_TARGETS = 'targets';
    public const KEY_UNITS = 'units';

    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     * @param string|null $mergeByDataKey
     *
     * @return string
     */
    public function format(CalculatorResponseInterface $calculatorResult, ?string $mergeByDataKey = null) : string
    {
        $data = $this->addReportData([], $calculatorResult);
        $data = $this->addSettings($data, $calculatorResult->getSettings());
        $data = $this->addTargetData($data, $calculatorResult);
        $data = $this->addDefense($data, $calculatorResult, $calculatorResult->getSettings());
        $data = $this->addOffense($data, $calculatorResult, $calculatorResult->getSettings());

        if ($mergeByDataKey !== null) {
           $data[static::KEY_OFFENSE] = $this->mergeByDataValue($data[static::KEY_OFFENSE], $mergeByDataKey);
           $data[static::KEY_DEFENSE] = $this->mergeByDataValue($data[static::KEY_DEFENSE], $mergeByDataKey);
        }

        $data[static::KEY_SUMMARY][static::KEY_DEFENSE] = $this->createMergedSummary(
            $data[static::KEY_DEFENSE], [], [static::KEY_DATA]
        );

        $data[static::KEY_SUMMARY][static::KEY_OFFENSE] = $this->createMergedSummary(
            $data[static::KEY_OFFENSE], [], [static::KEY_DATA]
        );

        if ($mergeByDataKey !== null) {
            $data[static::KEY_SUMMARY][static::KEY_DEFENSE][static::KEY_DATA] = array_merge(
                ...$data[static::KEY_SUMMARY][static::KEY_DEFENSE][static::KEY_DATA]
            );

            $data[static::KEY_SUMMARY][static::KEY_OFFENSE][static::KEY_DATA] = array_merge(
                ...$data[static::KEY_SUMMARY][static::KEY_OFFENSE][static::KEY_DATA]
            );
        }

        return $this->toJson($data);
    }

    /**
     * @param array $originalDataToMerge
     * @param string $mergeByDataKey
     *
     * @return string[]
     */
    private function mergeByDataValue(array $originalDataToMerge, string $mergeByDataKey): array
    {
        $groupedFleetArray = [];
        $mergedFleetArray = [];

        foreach ($originalDataToMerge as $originalDataToMergeItem) {
            if (!array_key_exists($mergeByDataKey, $originalDataToMergeItem[static::KEY_DATA])) {
                continue;
            }

            $groupedFleetArray[$originalDataToMergeItem[static::KEY_DATA][$mergeByDataKey]][] = $originalDataToMergeItem;
        }

        foreach ($groupedFleetArray as $groupedByKey => $groupedFleetArrays) {
            $mergedFleetArray[] = $this->createMergedSummary($groupedFleetArrays, [], [static::KEY_DATA]);
        }

        return $mergedFleetArray;
    }

    /**
     * @param string[] $originalDataToMerge
     * @param string[] $ignoreKeys - default: []
     * @param string[] $simpleMergeKey - default: []
     *
     * @return string[]
     */
    private function createMergedSummary(
        array $originalDataToMerge,
        array $ignoreKeys = [],
        array $simpleMergeKey = []
    ): array {
        $summary = [];

        foreach ($originalDataToMerge as $originalDataToMergeItemKey => $originalDataToMergeItemValue) {
            foreach ($originalDataToMergeItemValue as $dataToMergeItemKey => $dataToMergeItemValue) {
                if (in_array($dataToMergeItemKey, $ignoreKeys, true)) {
                    continue;
                }

                $isSimpleMerge = in_array($dataToMergeItemKey, $simpleMergeKey, true);
                if ($isSimpleMerge && is_array($dataToMergeItemValue)) {
                    $summary[$dataToMergeItemKey][] = $dataToMergeItemValue;
                    continue;
                }

                if (!array_key_exists($dataToMergeItemKey, $summary)) {
                    $summary[$dataToMergeItemKey] = $dataToMergeItemValue;
                    continue;
                }

                if (is_numeric($dataToMergeItemValue) && is_numeric($summary[$dataToMergeItemKey])) {
                    $summary[$dataToMergeItemKey] += $dataToMergeItemValue;
                    continue;
                }

                if (is_array($dataToMergeItemValue) && is_array($summary[$dataToMergeItemKey])) {
                    $summary[$dataToMergeItemKey] = $this->createMergedSummary(
                        [$summary[$dataToMergeItemKey], $dataToMergeItemValue],
                        $ignoreKeys,
                        $simpleMergeKey
                    );

                    continue;
                }

                $summary[$dataToMergeItemKey] = $dataToMergeItemValue;

            }
        }

        return $summary;
    }

    /**
     * @param string[] $data
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return array
     */
    private function addDefense(
        array $data,
        CalculatorResponseInterface $calculatorResult,
        SettingsInterface $settings
    ): array {
        $beforeFleets = $calculatorResult->getBeforeBattle()->getDefendingFleets();
        foreach ($beforeFleets as $beforeFleet) {
            $afterFleet = $calculatorResult->getAfterBattle()->getFleetById(
                $beforeFleet->getFleetId(),
                $calculatorResult->getAfterBattle()->getDefendingFleets()
            );

            $fleetArray = $this->addMetaDataArray([], $afterFleet);
            $fleetArray = $this->createFleetArray($fleetArray,$beforeFleet, $afterFleet, $settings);
            $fleetArray = $this->addDefenseResourceArray($fleetArray, $afterFleet);

            $data[static::KEY_DEFENSE][] = $fleetArray;
        }

        return $data;
    }

    /**
     * @param string[] $data
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return array
     */
    private function addOffense(
        array $data,
        CalculatorResponseInterface $calculatorResult,
        SettingsInterface $settings
    ): array {
        $beforeFleets = $calculatorResult->getBeforeBattle()->getAttackingFleets();

        foreach ($beforeFleets as $beforeFleet) {
            $afterFleet = $calculatorResult->getAfterBattle()->getFleetById(
                $beforeFleet->getFleetId(),
                $calculatorResult->getAfterBattle()->getAttackingFleets()
            );

            $fleetArray = $this->addMetaDataArray([], $afterFleet);
            $fleetArray = $this->createFleetArray($fleetArray, $beforeFleet, $afterFleet, $settings);
            $fleetArray = $this->addExtractorStolenArray($fleetArray, $afterFleet);

            $data[static::KEY_OFFENSE][] = $fleetArray;
        }

        return $data;
    }

    /**
     * @param string[] $data
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return array
     */
    private function addSettings(array $data, SettingsInterface $settings): array
    {
        $data[static::KEY_SETTINGS][static::KEY_COMBAT_TICKS] = $settings->getCombatTicks();
        $data[static::KEY_SETTINGS][static::KEY_IS_PRE_FIRE_TICK] = $settings->isPreFireTick();
        $data[static::KEY_SETTINGS][static::KEY_IS_LAST_TICK] = $settings->isLastTick();
        $data[static::KEY_SETTINGS][static::KEY_EXTRACTOR_STEAL_RATIO] = $settings->getExtractorStealRatio();
        $data[static::KEY_SETTINGS][static::KEY_TARGET_SALVAGE_RATIO] = $settings->getTargetSalvageRatio();
        $data[static::KEY_SETTINGS][static::KEY_DEFENDER_SALVAGE_RATIO] = $settings->getDefenderSalvageRatio();

        foreach ($settings->getUnits() as $unit) {
            $currentUnitData = [
                static::KEY_UNIT_ID => $unit->getUnitId(),
                static::KEY_NAME => $unit->getName(),
                static::KEY_GROUP => $unit->getGroup(),
                static::KEY_METAL => $unit->getMetalCost(),
                static::KEY_CRYSTAL => $unit->getCrystalCost(),
                static::KEY_EXTRACTOR_PROTECTION_AMOUNT => $unit->getExtractorProtectAmount(),
                static::KEY_EXTRACTOR_STEAL_AMOUNT => $unit->getExtractorStealAmount(),
                static::KEY_CARRIER_CAPACITY_RATIO => $unit->getCarrierCapacity(),
                static::KEY_CARRIER_CAPACITY_CONSUMED_RATIO => $unit->getCarrierCapacityConsumed(),
            ];

            foreach ($settings->getUnitCombatSettingTargetsOf($unit->getUnitId()) as $combatSetting) {
                $currentTargetData = [
                    static::KEY_UNIT_ID => $combatSetting->getTargetUnitId(),
                    static::KEY_ATTACK_POWER => $combatSetting->getAttackPower(),
                    static::KEY_DISTRIBUTION_RATIO => $combatSetting->getDistributionRatio(),
                ];

                $currentUnitData[static::KEY_TARGETS][] = $currentTargetData;
            }

            $data[static::KEY_SETTINGS][static::KEY_UNITS][] = $currentUnitData;
        }

        return $data;
    }

    /**
     * @param string[] $fleetArray
     * @param \GC\Combat\Model\FleetInterface $afterFleet
     *
     * @return array
     */
    private function addMetaDataArray(array $fleetArray, FleetInterface $afterFleet): array
    {
        $fleetArray[static::KEY_DATA] = $afterFleet->getData();
        $fleetArray[static::KEY_DATA][static::KEY_FLEET_ID] = $afterFleet->getFleetId();

        return $fleetArray;
    }

    /**
     * @param string[] $fleetArray
     * @param \GC\Combat\Model\FleetInterface $afterFleet
     *
     * @return array
     */
    private function addExtractorStolenArray(array $fleetArray, FleetInterface $afterFleet): array
    {
        $fleetArray[static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_STEAL_CAPACITY]
            = $afterFleet->getExtractorsStealCapacity();

        $fleetArray[static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_EXTRACTOR_STOLEN][static::KEY_METAL]
            = $afterFleet->getExtractorStolenMetal();

        $fleetArray[static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_EXTRACTOR_STOLEN][static::KEY_CRYSTAL]
            = $afterFleet->getExtractorStolenCrystal();

        return $fleetArray;
    }

    /**
     * @param string[] $fleetArray
     * @param \GC\Combat\Model\FleetInterface $afterFleet
     *
     * @return string[]
     */
    private function addDefenseResourceArray(array $fleetArray, FleetInterface $afterFleet): array
    {
        $fleetArray[static::KEY_RESOURCE][static::KEY_SALVAGED][static::KEY_METAL]= $afterFleet->getSalvagedMetal();
        $fleetArray[static::KEY_RESOURCE][static::KEY_SALVAGED][static::KEY_CRYSTAL]= $afterFleet->getSalvagedCrystal();
        $fleetArray[static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_PROTECTED]= $afterFleet->getExtractorsProtected();

        return $fleetArray;
    }

    /**
     * @param string[] $fleetArray
     * @param \GC\Combat\Model\FleetInterface $before
     * @param \GC\Combat\Model\FleetInterface $after
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return string[]
     */
    private function createFleetArray(array $fleetArray, FleetInterface $before, FleetInterface $after, SettingsInterface $settings): array
    {
        $unitsBefore = $this->sortArrayByKeyAndRemoveEmptyValues(
            $before->getUnits()
        );

        $unitsAfter = $this->sortArrayByKeyAndRemoveEmptyValues(
            $after->getUnits()
        );

        $afterUnitsDestroyed = $this->sortArrayByKeyAndRemoveEmptyValues(
            $after->getUnitsDestroyed()
        );

        $fleetArray[static::KEY_BEFORE] = $this->createFleetList($unitsBefore, $settings);
        $fleetArray[static::KEY_AFTER] = $this->createFleetList($unitsAfter, $settings);
        $fleetArray[static::KEY_DESTROYED] = $this->createFleetList($afterUnitsDestroyed, $settings);
        $fleetArray[static::KEY_CARRIER] = $this->createCarrierListFor($after, $settings);

        return $fleetArray;
    }

    /**
     * @param int[] $array
     *
     * @return int[]
     */
    private function sortArrayByKeyAndRemoveEmptyValues(array $array): array
    {
        ksort($array);
        return array_filter($array);
    }

    /**
     * @param int[] $fleet
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return string[]
     */
    private function createFleetList(array $fleet, SettingsInterface $settings): array
    {
        $unitArray = [];

        foreach ($fleet as $unitId => $quantity) {
            $unitArray[$settings->getUnitById($unitId)->getName()] = $quantity;
        }

        return $unitArray;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return string[]
     */
    private function createCarrierListFor(FleetInterface $fleet, SettingsInterface $settings): array
    {
        $carrierArray = [];
        $carrierArray[static::KEY_CARRIER_CAPACITY] = $fleet->getCarrierCapacity();
        $carrierArray[static::KEY_CARRIER_CAPACITY_CONSUMED] = $fleet->getCarrierCapacityConsumed();

        $carrierLossesArray = [];
        foreach ($fleet->getInsufficientCarrierCapacityLosses() as $unitId => $quantity) {
            $carrierLossesArray[$settings->getUnitById($unitId)->getName()] = $quantity;
        }

        $carrierArray[static::KEY_CARRIER_LOSSES] = $carrierLossesArray;

        return $carrierArray;
    }

    /**
     * @param string[] $data
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     *
     * @return string[]
     */
    private function addTargetData(array $data, CalculatorResponseInterface $calculatorResult): array
    {
        $data[static::KEY_TARGET][static::KEY_DATA] = $calculatorResult->getAfterBattle()->getTargetData();

        $data[static::KEY_TARGET][static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_BEFORE][static::KEY_METAL] =
            $calculatorResult->getBeforeBattle()->getTargetExtractorsMetal();

        $data[static::KEY_TARGET][static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_BEFORE][static::KEY_CRYSTAL] =
            $calculatorResult->getBeforeBattle()->getTargetExtractorsCrystal();

        $data[static::KEY_TARGET][static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_AFTER][static::KEY_METAL] =
            $calculatorResult->getAfterBattle()->getTargetExtractorsMetal();

        $data[static::KEY_TARGET][static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_AFTER][static::KEY_CRYSTAL] =
            $calculatorResult->getAfterBattle()->getTargetExtractorsCrystal();

        $data[static::KEY_TARGET][static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_EXTRACTOR_LOST][static::KEY_METAL] = $this->diff(
            $calculatorResult->getBeforeBattle()->getTargetExtractorsMetal(),
            $calculatorResult->getAfterBattle()->getTargetExtractorsMetal()
        );

        $data[static::KEY_TARGET][static::KEY_RESOURCE][static::KEY_EXTRACTOR][static::KEY_EXTRACTOR_LOST][static::KEY_CRYSTAL] = $this->diff(
            $calculatorResult->getBeforeBattle()->getTargetExtractorsCrystal(),
            $calculatorResult->getAfterBattle()->getTargetExtractorsCrystal()
        );

        return $data;
    }

    /**
     * @param string[] $data
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     *
     * @return string[]
     *@throws
     *
     */
    private function addReportData(array $data, CalculatorResponseInterface $calculatorResult): array
    {
        $data[static::KEY_REPORT] = $calculatorResult->getAfterBattle()->getData();
        $data[static::KEY_REPORT][static::KEY_CREATED_AT] = (new DateTimeImmutable())->format('Y-m-d H:i:s');

        return $data;
    }

    /**
     * @param int $before
     * @param int $after
     *
     * @return int
     */
    private function diff(int $before, int $after): int
    {
        return $before - $after;
    }

    /**
     * @param string[] $data
     *
     * @return string
     */
    private function toJson(array $data): string
    {
        return json_encode($data);
    }
}
