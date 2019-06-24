<?php

declare(strict_types=1);

namespace GC\Combat\Calculator;

use GC\Combat\Model\BattleInterface;

interface CalculatorResultInterface
{
    /**
     * @return \GC\Combat\Model\BattleInterface
     */
    public function getBeforeBattle(): BattleInterface;

    /**
     * @return \GC\Combat\Model\BattleInterface
     */
    public function getAfterBattle(): BattleInterface;
}
