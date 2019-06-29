<?php

declare(strict_types=1);

namespace GC\Combat\Calculator;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

interface CalculatorResponseInterface
{
    /**
     * @return \GC\Combat\Model\BattleInterface
     */
    public function getBeforeBattle(): BattleInterface;

    /**
     * @return \GC\Combat\Model\BattleInterface
     */
    public function getAfterBattle(): BattleInterface;

    /**
     * @return \GC\Combat\Model\SettingsInterface
     */
    public function getSettings(): SettingsInterface;

    /**
     * @return string[]
     */
    public function getMessages(): array;

    /**
     * @param string $message
     *
     * @return void
     */
    public function addMessage(string $message): void;
}
