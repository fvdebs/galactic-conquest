<?php

declare(strict_types=1);

namespace GC\Galaxy;

use Doctrine\ORM\EntityManager;
use GC\Galaxy\Model\GalaxyRepository;
use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class GalaxyServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideGalaxyRouteProvider($pimple);
        $this->provideGalaxyRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideGalaxyRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new GalaxyRouteProvider());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideGalaxyRepository(Container $container): void
    {
        $container->offsetSet(GalaxyRepository::class, function(Container $container) {
            return new GalaxyRepository($container->offsetGet(EntityManager::class));
        });
    }
}