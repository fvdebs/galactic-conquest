<?php

declare(strict_types=1);

namespace GC\Tick\Plugin;

use GC\Universe\Model\Universe;

interface TickPluginInterface
{
    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param \GC\Tick\Plugin\TickPluginResultInterface $tickPluginResult
     *
     * @return \GC\Tick\Plugin\TickPluginResultInterface
     */
    public function executeFor(Universe $universe, TickPluginResultInterface $tickPluginResult): TickPluginResultInterface;
}
