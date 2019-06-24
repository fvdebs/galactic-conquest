<?php

declare(strict_types=1);

namespace GC\Combat\Format;

use GC\Combat\Calculator\CalculatorResultInterface;
use GC\Combat\Model\SettingsInterface;

final class JsonFormatter implements JsonFormatterInterface
{
    /**
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return string
     */
    public function format(CalculatorResultInterface $calculatorResult, SettingsInterface $settings): string
    {
        return '{}';
    }
}
