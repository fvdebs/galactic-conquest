<?php

declare(strict_types=1);

namespace GC\Alliance;

use Doctrine\ORM\EntityManager;
use GC\Alliance\Model\AllianceRepository;
use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class AllianceServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideAllianceRouteProvider($pimple);
        $this->provideAllianceRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideAllianceRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new AllianceRouteProvider());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideAllianceRepository(Container $container): void
    {
        $container->offsetSet(AllianceRepository::class, function(Container $container) {
            return new AllianceRepository($container->offsetGet(EntityManager::class));
        });
    }
}