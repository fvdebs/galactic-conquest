<?php

declare(strict_types=1);

namespace GC\Combat\Calculator;

use GC\Combat\Model\BattleInterface;

final class CalculatorResult implements CalculatorResultInterface
{
    /**
     * @var \GC\Combat\Model\BattleInterface
     */
    private $beforeBattle;

    /**
     * @var \GC\Combat\Model\BattleInterface
     */
    private $afterBattle;

    /**
     * @param \GC\Combat\Model\BattleInterface $beforeBattle
     * @param \GC\Combat\Model\BattleInterface $afterBattle
     */
    public function __construct(BattleInterface $beforeBattle, BattleInterface $afterBattle)
    {
        $this->beforeBattle = $beforeBattle;
        $this->afterBattle = $afterBattle;
    }

    /**
     * @return \GC\Combat\Model\BattleInterface
     */
    public function getBeforeBattle(): BattleInterface
    {
        return $this->beforeBattle;
    }

    /**
     * @return \GC\Combat\Model\BattleInterface
     */
    public function getAfterBattle(): BattleInterface
    {
        return $this->afterBattle;
    }
}
