<?php

declare(strict_types=1);

namespace GC\Player;

use Doctrine\ORM\EntityManager;
use GC\Player\Model\PlayerRepository;
use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class PlayerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->providePlayerRouteProvider($pimple);
        $this->providePlayerRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function providePlayerRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new PlayerRouteProvider());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function providePlayerRepository(Container $container): void
    {
        $container->offsetSet(PlayerRepository::class, function(Container $container) {
            return new PlayerRepository($container->offsetGet(EntityManager::class));
        });
    }
}