<?php

declare(strict_types=1);

namespace GC\Home;

use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class HomeServiceProvider implements ServiceProviderInterface
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
    private function addHomeRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new HomeRouteProvider());
        });
    }
}