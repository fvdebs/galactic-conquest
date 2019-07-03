<?php

declare(strict_types=1);

namespace GC\Combat\Format;

use GC\Combat\Calculator\CalculatorResponseInterface;

interface JsonFormatterInterface
{
    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     *
     * @return string
     */
    public function format(CalculatorResponseInterface $calculatorResult) : string;
}
