<?php

declare(strict_types=1);

namespace GC\Combat\Format;

use GC\Combat\Calculator\CalculatorResultInterface;
use GC\Combat\Model\SettingsInterface;

interface JsonFormatterInterface
{
    /**
     * @param \GC\Combat\Calculator\CalculatorResultInterface $calculatorResult
     * @param \GC\Combat\Model\SettingsInterface $settings
     * @param string|null $mergeByDataKey
     *
     * @return string
     */
    public function format(
        CalculatorResultInterface $calculatorResult,
        SettingsInterface $settings,
        ?string $mergeByDataKey = null
    ) : string;
}
