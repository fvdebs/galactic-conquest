<?php

declare(strict_types=1);

namespace GC\Tick\Executor;

use GC\Tick\Plugin\TickPluginResultInterface;
use GC\Universe\Model\Universe;

interface TickExecutorResultInterface
{
    /**
     * @return \GC\Universe\Model\Universe
     */
    public function getUniverse(): Universe;

    /**
     * @return \GC\Tick\Plugin\TickPluginResultInterface[]
     */
    public function getPluginResults(): array;

    /**
     * @param \GC\Tick\Plugin\TickPluginResultInterface $pluginResult
     *
     * @return void
     */
    public function addPluginResult(TickPluginResultInterface $pluginResult): void;

    /**
     * @return float
     */
    public function getTimeOverall(): float;

    /**
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * @return \Throwable[]
     */
    public function getExceptions(): array;
}
