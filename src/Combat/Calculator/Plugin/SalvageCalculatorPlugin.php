<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

final class SalvageCalculatorPlugin implements CalculatorPluginInterface
{
    private const KEY_PLAYER_ID = 'playerId';

    /**
     * @param \GC\Combat\Model\BattleInterface $before
     * @param \GC\Combat\Model\BattleInterface $after
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Model\BattleInterface - $after
     */
    public function calculate(BattleInterface $before, BattleInterface $after, SettingsInterface $settings): BattleInterface
    {
        foreach ($after->getDefendingFleets() as $fleet) {

            $fleet->increaseUnitDestroyedQuantity(1, 20);
            if (!$fleet->hasUnitsDestroyed()) {
                continue;
            }

            $salvageModifier = $settings->getDefenderSalvagePerCentModifier();
            if ($after->compareFleetDataValueWithTargetDataValue($fleet, static::KEY_PLAYER_ID)) {
                // is target fleet
                $salvageModifier = $settings->getTargetSalvagePerCentModifier();
            }

            $this->calculateSalvageFor($fleet, $settings, $salvageModifier);
        }

        return $after;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param \GC\Combat\Model\SettingsInterface $settings
     * @param int $salvageModifier
     *
     * @return void
     */
    private function calculateSalvageFor(FleetInterface $fleet, SettingsInterface $settings, int $salvageModifier): void
    {
        $salvageMetal = 0;
        $salvageCrystal = 0;

        foreach ($fleet->getUnitsDestroyed() as $unitId => $quantity) {
            $unit = $settings->getUnitById($unitId);

            $metalCost = $unit->getMetalCost() * $quantity;
            $crystalCost = $unit->getCrystalCost() * $quantity;

            $salvageMetal += ($metalCost / 100) * $salvageModifier;
            $salvageCrystal += ($crystalCost / 100) * $salvageModifier;
        }

        $fleet->setSalvageMetal($salvageMetal);
        $fleet->setSalvageCrystal($salvageCrystal);
    }
}
