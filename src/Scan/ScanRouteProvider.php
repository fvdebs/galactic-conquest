<?php

declare(strict_types=1);

namespace GC\Scan;

use GC\Scan\Handler\ScanDetailHandler;
use GC\Scan\Handler\ScanHandler;
use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;

final class ScanRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        $collection->get('/{locale}/{universe}/scan/{scanId}', ScanDetailHandler::class);
        $collection->get('/{locale}/{universe}/scan/{scanType}', ScanHandler::class);
    }
}