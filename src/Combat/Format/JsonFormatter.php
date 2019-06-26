<?php

declare(strict_types=1);

namespace GC\Combat\Format;

use DateTimeImmutable;
use GC\Combat\Calculator\CalculatorResultInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

use function json_encode;

final class JsonFormatter implements JsonFormatterInterface
{
    private const KEY_REPORT = 'report';
    private const KEY_DATA = 'data';
    private const KEY_TARGET = 'target';
    private const KEY_DEFENSE = 'defense';
    private const KEY_OFFENSE = 'offense';
    private const KEY_SUMMARY = 'summary';
    private const KEY_RESOURCE = 'resource';
    private const KEY_SALVAGE = 'salvage';
    private const KEY_EXTRACTOR = 'extractor';
    private const KEY_EXTRACTOR_LOST = 'lost';
    private const KEY_EXTRACTOR_STOLEN = 'stolen';
    private const KEY_METAL = 'metal';
    private const KEY_CRYSTAL = 'crystal';
    private const KEY_CARRIER = 'carrier';
    private const KEY_CARRIER_SPACE = 'space';
    private const KEY_CARRIER_CONSUMPTION = 'consumption';
    private const KEY_CARRIER_LOSSES = 'losses';
    private const KEY_BEFORE = 'before';
    private const KEY_AFTER = 'after';
    private const KEY_DESTROYED = 'destroyed';
    private const KEY_FLEET_REFERENCE = 'fleetReference';
    private const KEY_CREATED_AT = 'createdAt';

    /**
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     * @param string|null $mergeByDataKey
     *
     * @throws
     *
     * @return string
     */
    public function format(
        CalculatorResultInterface $calculatorResult,
        SettingsInterface $settings,
        ?string $mergeByDataKey = null
    ) : string {
        $data = $this->addReportData([], $calculatorResult);
        $data = $this->addTargetData($data, $calculatorResult);
        $data = $this->addDefense($data, $calculatorResult, $settings);
        $data = $this->addOffense($data, $calculatorResult, $settings);

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
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return array
     */
    private function addDefense(
        array $data,
        CalculatorResultInterface $calculatorResult,
        SettingsInterface $settings
    ): array {
        $beforeFleets = $calculatorResult->getBeforeBattle()->getDefendingFleets();
        foreach ($beforeFleets as $beforeFleet) {
            $afterFleet = $calculatorResult->getAfterBattle()->getFleetByReference(
                $beforeFleet->getFleetReference(),
                $calculatorResult->getAfterBattle()->getDefendingFleets()
            );

            $fleetArray = $this->addMetaDataArray([], $afterFleet);
            $fleetArray = $this->createFleetArray($fleetArray,$beforeFleet, $afterFleet, $settings);
            $fleetArray = $this->addSalvageArray($fleetArray, $afterFleet);

            $data[static::KEY_DEFENSE][] = $fleetArray;
        }

        return $data;
    }

    /**
     * @param string[] $data
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return array
     */
    private function addOffense(
        array $data,
        CalculatorResultInterface $calculatorResult,
        SettingsInterface $settings
    ): array {
        $beforeFleets = $calculatorResult->getBeforeBattle()->getAttackingFleets();
        foreach ($beforeFleets as $beforeFleet) {
            $afterFleet = $calculatorResult->getAfterBattle()->getFleetByReference(
                $beforeFleet->getFleetReference(),
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
     * @param string[] $fleetArray
     * @param \GC\Combat\Model\FleetInterface $afterFleet
     *
     * @return array
     */
    private function addMetaDataArray(array $fleetArray, FleetInterface $afterFleet): array
    {
        $fleetArray[static::KEY_DATA] = $afterFleet->getData();
        $fleetArray[static::KEY_DATA][static::KEY_FLEET_REFERENCE] = $afterFleet->getFleetReference();

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
    private function addSalvageArray(array $fleetArray, FleetInterface $afterFleet): array
    {
        $fleetArray[static::KEY_RESOURCE][static::KEY_SALVAGE][static::KEY_METAL]= $afterFleet->getSalvageMetal();
        $fleetArray[static::KEY_RESOURCE][static::KEY_SALVAGE][static::KEY_CRYSTAL]= $afterFleet->getSalvageCrystal();

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
        $fleetArray[static::KEY_BEFORE] = $this->createFleetList($before->getUnits(), $settings);
        $fleetArray[static::KEY_AFTER] = $this->createFleetList($after->getUnits(), $settings);
        $fleetArray[static::KEY_DESTROYED] = $this->createFleetList($after->getUnitsDestroyed(), $settings);
        $fleetArray[static::KEY_CARRIER] = $this->createCarrierListFor($after, $settings);

        return $fleetArray;
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
        $carrierArray[static::KEY_CARRIER_SPACE] = $fleet->getCarrierSpace();
        $carrierArray[static::KEY_CARRIER_CONSUMPTION] = $fleet->getCarrierConsumption();

        $carrierLossesArray = [];
        foreach ($fleet->getInsufficientCarrierLosses() as $unitId => $quantity) {
            $carrierLossesArray[$settings->getUnitById($unitId)->getName()] = $quantity;
        }

        $carrierArray[static::KEY_CARRIER_LOSSES] = $carrierLossesArray;

        return $carrierArray;
    }

    /**
     * @param string[] $data
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     *
     * @return string[]
     */
    private function addTargetData(array $data, CalculatorResultInterface $calculatorResult): array
    {
        $data[static::KEY_TARGET][static::KEY_DATA] = $calculatorResult->getAfterBattle()->getTargetData();

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
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     *
     * @throws
     *
     * @return string[]
     */
    private function addReportData(array $data, CalculatorResultInterface $calculatorResult): array
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
