<?php

declare(strict_types=1);

namespace GC\User;

use Doctrine\ORM\EntityManager;
use GC\User\Model\UserRepository;
use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class UserServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideUserRouteProvider($pimple);
        $this->provideUserRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUserRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new UserRouteProvider());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUserRepository(Container $container): void
    {
        $container->offsetSet(UserRepository::class, function(Container $container) {
            return new UserRepository($container->offsetGet(EntityManager::class));
        });
    }
}