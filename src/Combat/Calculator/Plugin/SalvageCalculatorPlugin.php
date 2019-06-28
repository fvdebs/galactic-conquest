<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

use function array_merge;

final class SalvageCalculatorPlugin implements CalculatorPluginInterface
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
        /* @var \GC\Combat\Model\FleetInterface $fleet */
        $fleets = array_merge($after->getDefendingFleets(), $after->getAttackingFleets());

        $destroyedMetalTotal = $this->calculateDestroyedMetalTotal(
            $fleets,
            $settings
        );

        $destroyedCrystalTotal = $this->calculateDestroyedCrystalTotal(
            $fleets,
            $settings
        );

        $this->calculateSalvagedResources(
            $after,
            $destroyedMetalTotal,
            $destroyedCrystalTotal,
            $settings
        );

        return $after;
    }

    /**
     * @param \GC\Combat\Model\BattleInterface $after
     * @param float $destroyedMetalTotal
     * @param float $destroyedCrystalTotal
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return void
     */
    private function calculateSalvagedResources(
        BattleInterface $after,
        float $destroyedMetalTotal,
        float $destroyedCrystalTotal,
        SettingsInterface $settings
    ): void {
        foreach ($after->getDefendingFleets() as $fleet) {
            $salvageRatio = $settings->getDefenderSalvageRatio();
            if ( $after->isFleetFromTarget($fleet)) {
                $salvageRatio = $settings->getTargetSalvageRatio();
            }

            $salvagedMetal = ($destroyedMetalTotal / 100) * $salvageRatio;
            $salvagedCrystal = ($destroyedCrystalTotal / 100) * $salvageRatio;

            $fleet->setSalvagedMetal($salvagedMetal);
            $fleet->setSalvagedCrystal($salvagedCrystal);
        }
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return float
     */
    private function calculateDestroyedMetalTotal(array $fleets, SettingsInterface $settings): float
    {
        $destroyedMetal = 0;

        foreach ($fleets as $fleet) {
            foreach ($fleet->getUnitsDestroyed() as $unitId => $quantity) {
                $unit = $settings->getUnitById($unitId);

                $destroyedMetal += $unit->getMetalCost() * $quantity;
            }
        }

        return $destroyedMetal;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return float
     */
    private function calculateDestroyedCrystalTotal(array $fleets, SettingsInterface $settings): float
    {
        $destroyedCrystal = 0;

        foreach ($fleets as $fleet) {
            foreach ($fleet->getUnitsDestroyed() as $unitId => $quantity) {
                $unit = $settings->getUnitById($unitId);

                $destroyedCrystal += $unit->getCrystalCost() * $quantity;
            }
        }

        return $destroyedCrystal;
    }
}
