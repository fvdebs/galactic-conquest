<?php

declare(strict_types=1);

namespace GC\Universe;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class UniverseRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        // list of available universes
        $collection->get('/{locale}/universes', function(ContainerInterface $container) {

        }, 'name');
    }
}