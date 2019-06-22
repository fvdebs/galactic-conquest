<?php

declare(strict_types=1);

namespace GC\Tick\Executor;

use GC\Tick\Plugin\TickPluginResultInterface;
use GC\Universe\Model\Universe;

final class TickExecutorResult implements TickExecutorResultInterface
{
    /**
     * @var \GC\Tick\Plugin\TickPluginResultInterface[]
     */
    private $pluginResults = [];

    /**
     * @var \GC\Universe\Model\Universe
     */
    private $universe;

    /**
     * @param \GC\Universe\Model\Universe $universe
     */
    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    /**
     * @return \GC\Universe\Model\Universe
     */
    public function getUniverse(): Universe
    {
        return $this->universe;
    }

    /**
     * @return \GC\Tick\Plugin\TickPluginResultInterface[]
     */
    public function getPluginResults(): array
    {
        return $this->pluginResults;
    }

    /**
     * @param \GC\Tick\Plugin\TickPluginResultInterface $pluginResult
     *
     * @return void
     */
    public function addPluginResult(TickPluginResultInterface $pluginResult): void
    {
        $this->pluginResults[] = $pluginResult;
    }

    /**
     * @return float
     */
    public function getTimeOverall(): float
    {
        $time = 0;

        foreach ($this->pluginResults as $pluginResult) {
            $time += $pluginResult->getTime();
        }

        return $time;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        foreach ($this->pluginResults as $pluginResult) {
            if (!$pluginResult->isSuccessful()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return \Throwable[]
     */
    public function getExceptions(): array
    {
        $exceptions = [];

        foreach ($this->pluginResults as $pluginResult) {
            if ($pluginResult->hasException()) {
                $exceptions[] = $pluginResult->getException();
            }
        }

        return $exceptions;
    }
}
