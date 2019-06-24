<?php

declare(strict_types=1);

namespace GC\Combat\Service;

use GC\Combat\Calculator\CalculatorInterface;
use GC\Combat\Calculator\CalculatorResultInterface;
use GC\Combat\Format\JsonFormatterInterface;
use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

final class CombatService implements CombatServiceInterface
{
    /**
     * @var \GC\Combat\Calculator\CalculatorInterface
     */
    private $calculator;

    /**
     * @var \GC\Combat\Format\JsonFormatterInterface
     */
    private $jsonFormatter;

    /**
     * @param \GC\Combat\Calculator\CalculatorInterface $calculator
     * @param \GC\Combat\Format\JsonFormatterInterface $jsonFormatter
     */
    public function __construct(
        CalculatorInterface $calculator,
        JsonFormatterInterface $jsonFormatter
    ) {
        $this->calculator = $calculator;
        $this->jsonFormatter = $jsonFormatter;
    }

    /**
     * @param \GC\Combat\Model\BattleInterface $battle
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Calculator\CalculatorResultInterface
     */
    public function calculate(BattleInterface $battle, SettingsInterface $settings): CalculatorResultInterface
    {
        return $this->calculator->calculate($battle, $settings);
    }

    /**
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return string
     */
    public function formatJson(CalculatorResultInterface $calculatorResult, SettingsInterface $settings): string
    {
        return $this->jsonFormatter->format($calculatorResult, $settings);
    }
}

