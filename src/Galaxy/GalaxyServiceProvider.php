<?php

declare(strict_types=1);

namespace GC\Galaxy;

use GC\Galaxy\Handler\GalaxyAllianceApplicationHandler;
use GC\Galaxy\Handler\GalaxyAllianceApplicationSaveHandler;
use GC\Galaxy\Handler\GalaxyEditHandler;
use GC\Galaxy\Handler\GalaxyEditSaveHandler;
use GC\Galaxy\Handler\GalaxyMemberDetailHandler;
use GC\Galaxy\Handler\GalaxyOverviewHandler;
use GC\Galaxy\Handler\GalaxyTacticHandler;
use GC\Galaxy\Handler\GalaxyTechnologyBuildHandler;
use GC\Galaxy\Handler\GalaxyViewHandler;
use Inferno\Routing\Route\RouteCollection;
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
        $this->provideGalaxyRoutes($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideGalaxyRoutes(Container $container): void
    {
        $container->extend(RouteCollection::class, function(RouteCollection $collection, Container $container)
        {
            $collection->get('/{locale}/{universe}/galaxy/{number}', GalaxyViewHandler::class);
            $collection->get('/{locale}/{universe}/galaxy/edit', GalaxyEditHandler::class);
            $collection->get('/{locale}/{universe}/galaxy/overview', GalaxyOverviewHandler::class);
            $collection->get('/{locale}/{universe}/galaxy/tactic', GalaxyTacticHandler::class);
            $collection->get('/{locale}/{universe}/galaxy/tactic/{position}', GalaxyMemberDetailHandler::class);
            $collection->get('/{locale}/{universe}/galaxy/application', GalaxyAllianceApplicationHandler::class);

            $collection->post('/{locale}/{universe}/galaxy/edit', GalaxyEditSaveHandler::class);
            $collection->post('/{locale}/{universe}/galaxy/technology', GalaxyTechnologyBuildHandler::class);
            $collection->post('/{locale}/{universe}/galaxy/application', GalaxyAllianceApplicationSaveHandler::class);

            return $collection;
        });
    }
}