<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

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
        for ($i = 1; $i <= $settings->getCombatTicks(); $i++) {
            $after = $this->tick($before, $after, $settings);
        }

        return $after;
    }

    /**
     * @param \GC\Combat\Model\BattleInterface $before
     * @param \GC\Combat\Model\BattleInterface $after
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Model\BattleInterface $after
     */
    private function tick(BattleInterface $before, BattleInterface $after, SettingsInterface $settings)
    {
        return $after;
    }
}
