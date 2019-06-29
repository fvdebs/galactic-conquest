<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Calculator\CalculatorResponseInterface;

interface CalculatorPluginInterface
{
    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResponse
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface
     */
    public function calculate(CalculatorResponseInterface $calculatorResponse): CalculatorResponseInterface;
}
