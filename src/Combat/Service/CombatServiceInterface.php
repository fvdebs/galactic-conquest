<?php

declare(strict_types=1);

namespace GC\Combat\Service;

use GC\Combat\Calculator\CalculatorResultInterface;
use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;
use GC\Universe\Model\Universe;

interface CombatServiceInterface
{
    /**
     * @param \GC\Combat\Model\BattleInterface $battle
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Calculator\CalculatorResultInterface
     */
    public function calculate(BattleInterface $battle, SettingsInterface $settings): CalculatorResultInterface;

    /**
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return string
     */
    public function formatJson(CalculatorResultInterface $calculatorResult, SettingsInterface $settings): string;
}
