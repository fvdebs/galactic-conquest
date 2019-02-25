<?php

declare(strict_types=1);

namespace GC\Combat;

use Doctrine\ORM\EntityManager;
use GC\Combat\Model\CombatReportRepository;
use Inferno\Routing\Loader\RouteProviderLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class CombatServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideCombatRouteProvider($pimple);
        $this->provideCombatReportRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideCombatRouteProvider(Container $container): void
    {
        $container->extend(RouteProviderLoader::class, function(RouteProviderLoader $routeProviderLoader, Container $container) {
            return $routeProviderLoader->addRouteProvider(new CombatRouteProvider());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideCombatReportRepository(Container $container): void
    {
        $container->offsetSet(CombatReportRepository::class, function(Container $container) {
            return new CombatReportRepository($container->offsetGet(EntityManager::class));
        });
    }
}