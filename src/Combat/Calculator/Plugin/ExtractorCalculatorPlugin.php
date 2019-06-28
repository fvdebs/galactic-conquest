<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

use function floor;

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

        if ($extractorStealCapacityTotal === 0.0 || $extractorsProtectedTotal >= $extractorStealCapacityTotal) {
            return $after;
        }

        $totalMetalExtractorsToSteal = $after->getTargetExtractorsMetal() *  $settings->getExtractorStealRatio();
        $totalCrystalExtractorsToSteal = $after->getTargetExtractorsCrystal() *  $settings->getExtractorStealRatio();

        foreach ($after->getAttackingFleets() as $fleet) {
            if ($fleet->getExtractorsStealCapacity() === 0.0) {
                continue;
            }

            $extractorsStolenMetal = $totalMetalExtractorsToSteal / ($extractorStealCapacityTotal) * $fleet->getExtractorsStealCapacity();
            $extractorsStolenCrystal = $totalCrystalExtractorsToSteal / ($extractorStealCapacityTotal) * $fleet->getExtractorsStealCapacity();

            $fleet->setExtractorStolenMetal((int) floor($extractorsStolenMetal));
            $fleet->setExtractorStolenCrystal((int) floor($extractorsStolenCrystal));
        }

        return $after;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return float
     */
    private function calculateNumberOfProtectedExtractors(array $fleets, SettingsInterface $settings): float
    {
        $extractorsProtectedTotal = 0;

        foreach ($fleets as $fleet) {

            $currentFleetCanProtect = 0;

            foreach ($fleet->getUnits() as $unitId => $quantity) {
                $currentUnitTypeCanProtect = $settings->getUnitById($unitId)
                    ->getExtractorProtectAmount();

                $currentUnitQuantityCanProtect = $currentUnitTypeCanProtect * $quantity;

                $currentFleetCanProtect += $currentUnitQuantityCanProtect;
            }

            $extractorsProtectedTotal += $currentFleetCanProtect;

            $fleet->setExtractorsProtected($currentFleetCanProtect);
        }

        return $extractorsProtectedTotal;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return float
     */
    private function calculateNumberOfStealCapacity(array $fleets, SettingsInterface $settings): float
    {
        $extractorsStealCapacityTotal = 0;

        foreach ($fleets as $fleet) {

            $currentFleetCanSteal = 0;

            foreach ($fleet->getUnits() as $unitId => $quantity) {
                $currentUnitTypeCanSteal = $settings->getUnitById($unitId)
                    ->getExtractorStealAmount();

                $currentUnitQuantityCanSteal = $currentUnitTypeCanSteal * $quantity;

                $currentFleetCanSteal += $currentUnitQuantityCanSteal;
            }

            $extractorsStealCapacityTotal += $currentFleetCanSteal;

            $fleet->setExtractorsStealCapacity($currentFleetCanSteal);
        }

        return $extractorsStealCapacityTotal;
    }
}
