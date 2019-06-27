<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

use function round;

final class ExtractorCalculatorPlugin implements CalculatorPluginInterface
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
        $extractorsProtectedTotal = $this->calculateNumberOfProtectedExtractors(
            $after->getDefendingFleets(),
            $settings
        );

        $extractorStealCapacityTotal = $this->calculateNumberOfStealCapacity(
            $after->getAttackingFleets(),
            $settings
        );

        if ($extractorStealCapacityTotal === 0 || $extractorsProtectedTotal >= $extractorStealCapacityTotal) {
            return $after;
        }

        $extractorsUnprotected = $extractorStealCapacityTotal - $extractorsProtectedTotal;

        $stealRatio = $settings->getExtractorStealPerCentModifier() / 100;

        $totalMetalExtractorsToSteal = $after->getTargetExtractorsMetal() * ($stealRatio / 100);
        $totalCrystalExtractorsToSteal = $after->getTargetExtractorsCrystal() * ($stealRatio / 100);

        foreach ($after->getAttackingFleets() as $fleet) {
            if ($fleet->getExtractorsStealCapacity() === 0) {
                continue;
            }

            $extractorsStolenMetal =
                ($totalMetalExtractorsToSteal / $extractorStealCapacityTotal) * $fleet->getExtractorsStealCapacity();

            $extractorsStolenCrystal =
                ($totalCrystalExtractorsToSteal / $extractorStealCapacityTotal) * $fleet->getExtractorsStealCapacity();

            $extractorsTotalPossibleToSteal = $extractorsStolenMetal + $extractorsStolenCrystal;

            if ($extractorsUnprotected > $extractorsTotalPossibleToSteal) {
                $extractorsStolenMetal = $extractorsStolenMetal * ($extractorsUnprotected / 100);
                $extractorsStolenCrystal = $extractorsStolenCrystal * ($extractorsUnprotected / 100);
            }

            $fleet->setExtractorStolenMetal((int) floor($extractorsStolenMetal));
            $fleet->setExtractorStolenCrystal((int) floor($extractorsStolenCrystal));
        }

        return $after;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return int
     */
    private function calculateNumberOfProtectedExtractors(array $fleets, SettingsInterface $settings): int
    {
        $extractorsProtectedTotal = 0;

        foreach ($fleets as $fleet) {

            $currentFleetCanProtect = 0;

            foreach ($fleet->getUnits() as $unitId => $quantity) {
                $currentUnitTypeCanProtect = $settings->getUnitById($unitId)
                    ->getExtractorGuardAmount();

                $currentUnitCanProtect = $currentUnitTypeCanProtect * $quantity;
                $currentFleetCanProtect += $currentUnitCanProtect;
            }

            $extractorsProtectedTotal += $currentFleetCanProtect;

            $fleet->setExtractorsGuarded((int) round($currentFleetCanProtect));
        }

        return (int) round($extractorsProtectedTotal);
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return int
     */
    private function calculateNumberOfStealCapacity(array $fleets, SettingsInterface $settings): int
    {
        $extractorsStealCapacityTotal = 0;

        foreach ($fleets as $fleet) {

            $currentFleetCanSteal = 0;

            foreach ($fleet->getUnits() as $unitId => $quantity) {
                $currentUnitTypeCanSteal = $settings->getUnitById($unitId)
                    ->getExtractorStealAmount();

                $currentUnitCanSteal = $currentUnitTypeCanSteal * $quantity;
                $currentFleetCanSteal += $currentUnitCanSteal;
            }

            $extractorsStealCapacityTotal += $currentFleetCanSteal;

            $fleet->setExtractorsStealCapacity((int) round($currentFleetCanSteal));
        }

        return (int) round($extractorsStealCapacityTotal);
    }
}
