<?php

declare(strict_types=1);

namespace GC\Alliance;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class AllianceRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        $collection->get('/{locale}/{universe}/alliance', function(ContainerInterface $container) {

        }, 'name');
    }
}