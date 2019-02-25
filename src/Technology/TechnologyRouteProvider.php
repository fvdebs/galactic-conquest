<?php

declare(strict_types=1);

namespace GC\Technology;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class TechnologyRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        // list of available technologies
        $collection->get('/{locale}/{universe}/technology', function(ContainerInterface $container) {

        }, 'technology');
    }
}