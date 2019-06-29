<?php

declare(strict_types=1);

namespace GC\Combat\Calculator;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;

final class CalculatorResponse implements CalculatorResponseInterface
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
     * @var \GC\Combat\Model\SettingsInterface
     */
    private $settings;

    /**
     * @var string[]
     */
    private $messages = [];

    /**
     * @param \GC\Combat\Model\BattleInterface $beforeBattle
     * @param \GC\Combat\Model\BattleInterface $afterBattle
     * @param \GC\Combat\Model\SettingsInterface $settings
     */
    public function __construct(
        BattleInterface $beforeBattle,
        BattleInterface $afterBattle,
        SettingsInterface $settings
    ) {
        $this->beforeBattle = $beforeBattle;
        $this->afterBattle = $afterBattle;
        $this->settings = $settings;
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

    /**
     * @return \GC\Combat\Model\SettingsInterface
     */
    public function getSettings(): SettingsInterface
    {
        return $this->settings;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }
}
