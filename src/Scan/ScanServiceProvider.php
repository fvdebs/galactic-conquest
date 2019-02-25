<?php

declare(strict_types=1);

namespace GC\Scan;

use Doctrine\ORM\EntityManager;
use GC\Scan\Model\ScanRepository;
use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class ScanServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideScanRouteProvider($pimple);
        $this->provideScanRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideScanRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new ScanRouteProvider());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideScanRepository(Container $container): void
    {
        $container->offsetSet(ScanRepository::class, function(Container $container) {
            return new ScanRepository($container->offsetGet(EntityManager::class));
        });
    }
}