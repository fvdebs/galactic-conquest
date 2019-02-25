<?php

declare(strict_types=1);

namespace GC\Universe;

use Doctrine\ORM\EntityManager;
use GC\Universe\Model\UniverseRepository;
use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class UniverseServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideUniverseRouteProvider($pimple);
        $this->provideUniverseRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUniverseRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new UniverseRouteProvider());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUniverseRepository(Container $container): void
    {
        $container->offsetSet(UniverseRepository::class, function(Container $container) {
            return new UniverseRepository($container->offsetGet(EntityManager::class));
        });
    }
}