<?php

declare(strict_types=1);

namespace GC\Technology;

use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class TechnologyServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->addHomeRouteProvider($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    protected function addHomeRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            $routeProviderLoader->addRouteProvider(new HomeRouteProvider());

            return $routeProviderLoader;
        });
    }
}
