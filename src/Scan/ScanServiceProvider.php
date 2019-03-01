<?php

declare(strict_types=1);

namespace GC\Scan;

use GC\Scan\Handler\ScanDetailHandler;
use GC\Scan\Handler\ScanHandler;
use Inferno\Routing\Route\RouteCollection;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class ScanServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideScanRoutes($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideScanRoutes(Container $container): void
    {
        $container->extend(RouteCollection::class, function(RouteCollection $collection, Container $container)
        {
            $collection->get('/{locale}/{universe}/scan/{scanId}', ScanDetailHandler::class);
            $collection->get('/{locale}/{universe}/scan/{scanType}', ScanHandler::class);

            return $collection;
        });
    }
}