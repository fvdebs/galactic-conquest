<?php

declare(strict_types=1);

namespace GC\Combat\Calculator;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

final class Calculator implements CalculatorInterface
{
    /**
     * @param \GC\Combat\Model\BattleInterface $battle
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Calculator\CalculatorResultInterface
     */
    public function calculate(BattleInterface $battle, SettingsInterface $settings): CalculatorResultInterface
    {
        return new CalculatorResult($battle, clone $battle);
    }
}
