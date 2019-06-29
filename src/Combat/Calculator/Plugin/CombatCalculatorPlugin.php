<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Model\CombatTickResult;
use GC\Combat\Model\CombatTickResultInterface;
use GC\Combat\Model\SettingsInterface;

use function array_sum;
use function array_diff_key;
use function array_values;
use function array_keys;
use function in_array;
use function count;

final class CombatCalculatorPlugin implements CalculatorPluginInterface
{
    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResponse
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface
     */
    public function calculate(CalculatorResponseInterface $calculatorResponse): CalculatorResponseInterface
    {
        $afterBattle = $calculatorResponse->getAfterBattle();
        $settings = $calculatorResponse->getSettings();

        $defenderFleets = [];
        $aggressorFleets = [];

        for ($currentCombatTick = 1; $currentCombatTick <= $settings->getCombatTicks(); $currentCombatTick++) {
            $defenderFleets = $afterBattle->getDefendingFleets();
            $aggressorFleets = $afterBattle->getAttackingFleets();

            $unitTotalDefender = $afterBattle->getUnitSumFromFleets($defenderFleets);
            $unitTotalAggressor = $afterBattle->getUnitSumFromFleets($aggressorFleets);

            $combatTickResultDefender = $this->calculateCombatTick($unitTotalDefender, $unitTotalAggressor, $settings);
            $combatTickResultAggressor = $this->calculateCombatTick($unitTotalAggressor, $unitTotalDefender, $settings);

            $this->increaseUnitDestroyedQuantityProportionallyFor(
                $defenderFleets,
                $combatTickResultDefender,
                $unitTotalDefender
            );

            $this->increaseUnitDestroyedQuantityProportionallyFor(
                $aggressorFleets,
                $combatTickResultAggressor,
                $unitTotalAggressor
            );

            $this->decreaseUnitQuantityProportionallyFrom(
                $defenderFleets,
                $combatTickResultAggressor,
                $unitTotalDefender
            );

            $this->decreaseUnitQuantityProportionallyFrom(
                $aggressorFleets,
                $combatTickResultDefender,
                $unitTotalAggressor
            );
        }

        foreach (array_merge($defenderFleets, $aggressorFleets) as $fleet) {
            /* @var \GC\Combat\Model\FleetInterface $fleet */
            $fleet->floorUnitQuantities();
        }

        return $calculatorResponse;
    }

    /**
     * @param int[] $unitSumAttacking
     * @param int[] $unitSumDefending
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Model\CombatTickResultInterface
     */
    private function calculateCombatTick(array $unitSumAttacking, array $unitSumDefending, SettingsInterface $settings): CombatTickResultInterface
    {
        $combatTickResult = new CombatTickResult();

        foreach ($unitSumAttacking as $unitIdWhichCurrentlyFiring => $unitQuantityWhichCurrentlyFiring) {
            if ($unitQuantityWhichCurrentlyFiring === 0.0) {
                continue;
            }

            $combatSettingsForCurrentUnit = $settings->getUnitCombatSettingTargetsOf(
                $unitIdWhichCurrentlyFiring
            );

            if (count($combatSettingsForCurrentUnit) === 0.0) {
                continue;
            }

            $targetUnitIds = $this->getTargetUnitIdsFrom($combatSettingsForCurrentUnit);

            $targetUnitsSum = $this->getUnitsByIdsFrom(
                $targetUnitIds,
                $unitSumDefending
            );

            if (count($targetUnitsSum) === 0.0) {
                continue;
            }

            $unitIdDistributionsFireCalculated = $this->calculateDistributionFireForUnitIds(
                $this->getDistributionRatiosFrom($combatSettingsForCurrentUnit),
                $targetUnitsSum
            );

            $unitIdAttackPowers = $this->getAttackPowersFrom(
                $combatSettingsForCurrentUnit
            );

            // fight
            foreach ($targetUnitsSum as $targetUnitId => $targetUnitQuantity) {
                $attackPowerForCurrentTarget = $unitIdAttackPowers[$targetUnitId];
                $distributionForCurrentTarget = $unitIdDistributionsFireCalculated[$targetUnitId];

                // calculate distribution power percentage
                $attackPowerForCurrentTargetDistributed = $attackPowerForCurrentTarget * $distributionForCurrentTarget;

                // reduce attack power by number of combat ticks
                $attackPowerForCurrentTargetDistributedTick = $attackPowerForCurrentTargetDistributed / $settings->getCombatTicks();

                // calculate destroyed units
                $destroyedQuantity = $unitQuantityWhichCurrentlyFiring * $attackPowerForCurrentTargetDistributedTick;

                // add
                if ($destroyedQuantity > $targetUnitQuantity) {
                    $destroyedQuantity = $targetUnitQuantity;
                }

                $destroyableLeft = $unitSumDefending[$targetUnitId] - $combatTickResult->getDestroyedUnitQuantityOf($targetUnitId);
                if ($destroyedQuantity >= $destroyableLeft) {
                    $destroyedQuantity = $destroyableLeft;
                }

                if ($destroyedQuantity === 0.0) {
                    continue;
                }

                $combatTickResult->addDestroyedUnit($targetUnitId, $destroyedQuantity);
                $combatTickResult->addDestroyedByUnit($unitIdWhichCurrentlyFiring, $targetUnitId, $destroyedQuantity);
            }
        }

        return $combatTickResult;
    }

    /**
     * @param int[] $unitIdDistributionRatios
     * @param int[] $targetUnitsSum
     *
     * @return int[]
     */
    private function calculateDistributionFireForUnitIds(array $unitIdDistributionRatios, array $targetUnitsSum): array
    {
        if (count($unitIdDistributionRatios) === count($targetUnitsSum)) {
            // no distribution ratio calculation needed because aggressors have all target units in fleet.
            return $unitIdDistributionRatios;
        }

        if (count($targetUnitsSum) === 1) {
            // no distribution ratio calculation needed because there is just one possible target.
            return [array_keys($targetUnitsSum)[0] => 1];
        }

        $unitsWhichAreNotInTargetUnitsSum = array_diff_key($unitIdDistributionRatios, $targetUnitsSum);
        $distributionUsed = 1 - array_sum(array_values($unitsWhichAreNotInTargetUnitsSum));

        // calculate distribution ratio.
        $unitIdDistributionRatiosCalculated = [];

        foreach ($targetUnitsSum as $unitId => $quantity) {
            $distributionRatioForCurrentUnit = $unitIdDistributionRatios[$unitId];

            $distributionRatioCalculated = $distributionRatioForCurrentUnit /  $distributionUsed;

            $unitIdDistributionRatiosCalculated[$unitId] = $distributionRatioCalculated;
        }

        return $unitIdDistributionRatiosCalculated;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\CombatTickResultInterface $combatTickResult
     * @param int[] $unitTotal
     *
     * @return void
     */
    private function increaseUnitDestroyedQuantityProportionallyFor(
        array $fleets,
        CombatTickResultInterface $combatTickResult,
        array $unitTotal
    ): void {
        foreach ($combatTickResult->getDestroyedByUnit() as $attackingUnitId => $targetUnitArray) {
            foreach ($targetUnitArray as $targetUnitId => $destroyedQuantity) {
                foreach ($fleets as $fleet) {
                    $unitQuantityOfCurrentFleetWhichDestroyedTarget = $fleet->getQuantityOf($attackingUnitId);
                    if ($unitQuantityOfCurrentFleetWhichDestroyedTarget === 0.0) {
                        continue;
                    }

                    $unitQuantityWhichDestroyedTargetOverall = $unitTotal[$attackingUnitId];

                    $destroyedQuantityCalculated =
                        ($destroyedQuantity / $unitQuantityWhichDestroyedTargetOverall) * $unitQuantityOfCurrentFleetWhichDestroyedTarget;

                    if ($destroyedQuantityCalculated === 0.0) {
                        continue;
                    }

                    $fleet->addUnitDestroyedQuantity($targetUnitId, $destroyedQuantityCalculated);
                }
            }
        }
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\CombatTickResultInterface $combatTickResult
     * @param int[] $unitSum
     *
     * @return void
     */
    private function decreaseUnitQuantityProportionallyFrom(
        array $fleets,
        CombatTickResultInterface $combatTickResult,
        array $unitSum
    ): void {
        foreach ($combatTickResult->getDestroyedUnit() as $destroyedUnitId => $destroyedQuantityTotal) {
            foreach ($fleets as $fleet) {
                $quantityOfCurrentFleet = $fleet->getQuantityOf($destroyedUnitId);
                if ($quantityOfCurrentFleet === 0.0) {
                    continue;
                }

                $totalUnitsOfDestroyedUnit = $unitSum[$destroyedUnitId];

                $quantityToLose = ($destroyedQuantityTotal / $totalUnitsOfDestroyedUnit) * $quantityOfCurrentFleet;

                $fleet->destroyUnit($destroyedUnitId, $quantityToLose);
            }
        }
    }

    /**
     * @param \GC\Combat\Model\UnitCombatSetting[] $unitCombatSettings
     *
     * @return int[]
     */
    private function getTargetUnitIdsFrom(array $unitCombatSettings): array
    {
        $targetUnitIds = [];

        foreach ($unitCombatSettings as $unitCombatSetting) {
            $targetUnitIds[] = $unitCombatSetting->getTargetUnitId();
        }

        return $targetUnitIds;
    }

    /**
     * @param \GC\Combat\Model\UnitCombatSetting[] $unitCombatSettings
     *
     * @return int[]
     */
    private function getAttackPowersFrom(array $unitCombatSettings): array
    {
        $unitIdAttackPowers = [];

        foreach ($unitCombatSettings as $unitCombatSetting) {
            $unitIdAttackPowers[$unitCombatSetting->getTargetUnitId()] = $unitCombatSetting->getAttackPower();
        }

        return $unitIdAttackPowers;
    }

    /**
     * @param \GC\Combat\Model\UnitCombatSetting[] $unitCombatSettings
     *
     * @return int[]
     */
    private function getDistributionRatiosFrom(array $unitCombatSettings): array
    {
        $unitIdDistributionsFire = [];

        foreach ($unitCombatSettings as $unitCombatSetting) {
            $unitIdDistributionsFire[$unitCombatSetting->getTargetUnitId()] = $unitCombatSetting->getDistributionRatio();
        }

        return $unitIdDistributionsFire;
    }

    /**
     * @param int[] $unitIds
     * @param int[] $unitSum
     *
     * @return int[]
     */
    private function getUnitsByIdsFrom(array $unitIds, array $unitSum): array
    {
        $filteredUnitSum = [];

        foreach ($unitSum as $unitId => $quantity) {
            if (!in_array($unitId, $unitIds, true)) {
                continue;
            }

            $filteredUnitSum[$unitId] = $quantity;
        }

        return $filteredUnitSum;
    }
}
