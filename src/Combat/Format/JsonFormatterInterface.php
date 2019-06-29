<?php

declare(strict_types=1);

namespace GC\Combat\Format;

use GC\Combat\Calculator\CalculatorResponseInterface;

interface JsonFormatterInterface
{
    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     * @param string|null $mergeByDataKey
     *
     * @return string
     */
    public function format(CalculatorResponseInterface $calculatorResult, ?string $mergeByDataKey = null) : string;
}
