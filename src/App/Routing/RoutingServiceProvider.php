<?php

declare(strict_types=1);

namespace GC\App\Routing;

use GC\App\Routing\Route\RouteCollection;
use GC\App\Routing\Strategy\GCHandlerStrategy;
use Inferno\Routing\Loader\RouteProviderLoader;
use Inferno\Routing\RoutingServiceProvider as InfernoRoutingServiceProvider;
use Inferno\Routing\Strategy\StrategyCollection;
use Inferno\Routing\Strategy\StrategyCollectionInterface;
use Pimple\Container;

class RoutingServiceProvider extends InfernoRoutingServiceProvider
{

    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        parent::register($pimple);

        $this->provideGCHandlerStrategy($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    protected function provideRouteProviderLoader(Container $container): void
    {
        $container->offsetSet(RouteProviderLoader::class, function(Container $container) {
            return new RouteProviderLoader(new RouteCollection());
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    protected function provideGCHandlerStrategy(Container $container): void
    {
        $container->extend(StrategyCollection::class, function(StrategyCollectionInterface $strategyCollection, Container $container) {
            $strategyCollection->addStrategy(new GCHandlerStrategy(new \Pimple\Psr11\Container($container)));
        });
    }
}