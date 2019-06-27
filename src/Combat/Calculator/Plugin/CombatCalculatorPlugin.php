<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\CombatTickResult;
use GC\Combat\Model\CombatTickResultInterface;
use GC\Combat\Model\SettingsInterface;

use function array_sum;
use function array_diff_key;
use function array_values;
use function array_keys;
use function array_map;
use function round;

final class CombatCalculatorPlugin implements CalculatorPluginInterface
{
    /**
     * @param \GC\Combat\Model\BattleInterface $before
     * @param \GC\Combat\Model\BattleInterface $after
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Model\BattleInterface - $after
     */
    public function calculate(BattleInterface $before, BattleInterface $after, SettingsInterface $settings): BattleInterface
    {
        $defenderFleets = [];
        $aggressorFleets = [];

        for ($i = 1; $i <= $settings->getCombatTicks(); $i++) {
            $defenderFleets = $after->getDefendingFleets();
            $aggressorFleets = $after->getAttackingFleets();

            $unitTotalDefender = $after->getUnitSumFromFleets($defenderFleets);
            $unitTotalAggressor = $after->getUnitSumFromFleets($aggressorFleets);

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
            $fleet->normalize();
        }

        return $after;
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
            if ($unitQuantityWhichCurrentlyFiring == 0) {
                continue;
            }

            $combatSettingsForCurrentUnit = $settings->getUnitCombatSettingsTargetsOf(
                $unitIdWhichCurrentlyFiring
            );

            if (count($combatSettingsForCurrentUnit) == 0) {
                continue;
            }

            $targetUnitIds = $this->getTargetUnitIdsFrom($combatSettingsForCurrentUnit);

            $targetUnitsSum = $this->getUnitsByIdsFrom(
                $targetUnitIds,
                $unitSumDefending
            );

            if (count($targetUnitsSum) == 0) {
                continue;
            }

            $unitIdDistributionsFireCalculated = $this->calculateDistributionFireForUnitIds(
                $this->getDistributionFireFrom($combatSettingsForCurrentUnit),
                $targetUnitsSum
            );

            $unitIdAttackPowers = $this->getAttackPowerFrom(
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

                if ($destroyedQuantity == 0) {
                    continue;
                }

                $combatTickResult->addDestroyedUnit($targetUnitId, $destroyedQuantity);
                $combatTickResult->addDestroyedByUnit($unitIdWhichCurrentlyFiring, $targetUnitId, $destroyedQuantity);
            }
        }

        return $combatTickResult;
    }

    /**
     * @param int[] $unitIdDistributionsFire
     * @param int[] $targetUnitsSum
     *
     * @return int[]
     */
    private function calculateDistributionFireForUnitIds(array $unitIdDistributionsFire, array $targetUnitsSum): array
    {
        $unitIdDistributionsFireCount = count($unitIdDistributionsFire);
        $targetUnitSumCount = count($targetUnitsSum);

        if ($unitIdDistributionsFireCount === $targetUnitSumCount) {
            // no need for calculation. aggressors have all target units
            return array_map(
                function(int $distributionValue) {
                    return $distributionValue / 100;
                }, $unitIdDistributionsFire
            );
        }

        if ($targetUnitSumCount === 1) {
            // no calculation needed cause there is just one possible target
            return [array_keys($targetUnitsSum)[0] => 1];
        }

        $unitsWhichAreNotInTargetUnitsSum = array_diff_key($unitIdDistributionsFire, $targetUnitsSum);
        $distributionUsed = 100 - array_sum(array_values($unitsWhichAreNotInTargetUnitsSum));

        // calculate distribution fire proportionally
        $unitIdDistributionsFireCalculated = [];
        foreach ($targetUnitsSum as $unitId => $quantity) {
            $baseDistributionFireForCurrentUnit = $unitIdDistributionsFire[$unitId];

            $distributionFireCalculated = $baseDistributionFireForCurrentUnit /  $distributionUsed;

            $unitIdDistributionsFireCalculated[$unitId] = $distributionFireCalculated;
        }

        return $unitIdDistributionsFireCalculated;
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
                    if ($unitQuantityOfCurrentFleetWhichDestroyedTarget == 0) {
                        continue;
                    }

                    $unitQuantityWhichDestroyedTargetOverall = $unitTotal[$attackingUnitId];

                    $destroyedQuantityCalculated =
                        ($destroyedQuantity / $unitQuantityWhichDestroyedTargetOverall) * $unitQuantityOfCurrentFleetWhichDestroyedTarget;

                    $destroyedQuantityCalculatedRounded = $destroyedQuantityCalculated;

                    if ($destroyedQuantityCalculatedRounded == 0) {
                        continue;
                    }

                    $fleet->addUnitDestroyedQuantity($targetUnitId, $destroyedQuantityCalculatedRounded);
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
                if ($quantityOfCurrentFleet == 0) {
                    continue;
                }

                $totalUnitsOfDestroyedUnit = $unitSum[$destroyedUnitId];

                $quantityToLose = ($destroyedQuantityTotal / $totalUnitsOfDestroyedUnit) * $quantityOfCurrentFleet;

                $quantityToLoseRounded = $quantityToLose;

                $fleet->destroyUnit($destroyedUnitId, $quantityToLoseRounded);
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
    private function getAttackPowerFrom(array $unitCombatSettings): array
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
    private function getDistributionFireFrom(array $unitCombatSettings): array
    {
        $unitIdDistributionsFire = [];

        foreach ($unitCombatSettings as $unitCombatSetting) {
            $unitIdDistributionsFire[$unitCombatSetting->getTargetUnitId()] = $unitCombatSetting->getDistributionPerCent();
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
            if (!in_array($unitId, $unitIds)) {
                continue;
            }

            $filteredUnitSum[$unitId] = $quantity;
        }

        return $filteredUnitSum;
    }
}
