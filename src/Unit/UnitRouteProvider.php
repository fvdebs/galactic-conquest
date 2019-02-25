<?php

declare(strict_types=1);

namespace GC\Unit;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class UnitRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        // list of available units
        $collection->get('/{locale}/{universe}/unit', function(ContainerInterface $container) {

        }, 'unit');
    }
}