<?php

declare(strict_types=1);

namespace GC\Combat\Calculator;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

final class Calculator implements CalculatorInterface
{
    /**
     * @var \GC\Combat\Calculator\Plugin\CalculatorPluginInterface[]
     */
    private $calculatorPlugins;

    /**
     * @param \GC\Combat\Calculator\Plugin\CalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(array $calculatorPlugins = [])
    {
        $this->calculatorPlugins = $calculatorPlugins;
    }

    /**
     * @param \GC\Combat\Model\BattleInterface $battle
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface
     */
    public function calculate(BattleInterface $battle, SettingsInterface $settings): CalculatorResponseInterface
    {
        $calculatorResponse = new CalculatorResponse($battle, clone $battle, $settings);

        foreach ($this->calculatorPlugins as $calculatorPlugin) {
            $calculatorResponse = $calculatorPlugin->calculate($calculatorResponse);
        }

        return $calculatorResponse;
    }
}
