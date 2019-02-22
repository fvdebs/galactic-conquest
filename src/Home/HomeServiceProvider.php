<?php

declare(strict_types=1);

namespace GC\Home;

use GC\User\Model\UserRepository;
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
        $this->addHomeFactory($pimple);
        $this->addHomeRouteProvider($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    protected function addHomeFactory(Container $container): void
    {
        $container->offsetSet(HomeFactory::class, function(Container $container) {
            return new HomeFactory(
                $container->offsetGet('response-factory'),
                $container->offsetGet('renderer'),
                $container->offsetGet(UserRepository::class)
            );
        });
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
