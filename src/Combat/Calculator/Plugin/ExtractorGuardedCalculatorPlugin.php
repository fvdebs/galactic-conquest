<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

final class ExtractorGuardedCalculatorPlugin implements CalculatorPluginInterface
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
        foreach ($after->getDefendingFleets() as $fleet) {
            $this->calculateExtractorsGuarded($fleet, $settings);
        }

        return $after;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return void
     */
    private function calculateExtractorsGuarded(FleetInterface $fleet, SettingsInterface $settings): void
    {
        $extractorsGuarded = 0;

        foreach ($fleet->getUnits() as $unitId => $quantity) {
            $unit = $settings->getUnitById($unitId);

            $extractorsGuarded += $unit->getExtractorGuardAmount() * $quantity;
        }

        $fleet->setExtractorsGuarded($extractorsGuarded);
    }
}
