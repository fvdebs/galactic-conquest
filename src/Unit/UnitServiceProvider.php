<?php

declare(strict_types=1);

namespace GC\Unit;

use Doctrine\ORM\EntityManager;
use GC\Unit\Model\UnitRepository;
use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class UnitServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideUnitRouteProvider($pimple);
        $this->provideUnitRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUnitRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new UnitRouteProvider());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUnitRepository(Container $container): void
    {
        $container->offsetSet(UnitRepository::class, function(Container $container) {
            return new UnitRepository($container->offsetGet(EntityManager::class));
        });
    }
}