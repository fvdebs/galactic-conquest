<?php

declare(strict_types=1);

namespace GC\Galaxy;

use GC\Galaxy\Handler\GalaxiesHandler;
use GC\Galaxy\Handler\GalaxyAllianceApplicationHandler;
use GC\Galaxy\Handler\GalaxySettingsHandler;
use GC\Galaxy\Handler\GalaxyTacticHandler;
use GC\Galaxy\Handler\GalaxyTacticMilitaryHandler;
use GC\Galaxy\Handler\GalaxyTechnologyBuildHandler;
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
        $container->extend(RouteCollection::class, function (RouteCollection $collection, Container $container)
        {
            $collection->get('/{locale}/{universe}/galaxy/{number}', GalaxiesHandler::class);
            $collection->get('/{locale}/{universe}/galaxy/tactic', GalaxyTacticHandler::class);
            $collection->get('/{locale}/{universe}/galaxy/military/{position}', GalaxyTacticMilitaryHandler::class);
            $collection->get('/{locale}/{universe}/galaxy', GalaxySettingsHandler::class);

            $collection->post('/{locale}/{universe}/galaxy/technology', GalaxyTechnologyBuildHandler::class);
            $collection->post('/{locale}/{universe}/galaxy/application', GalaxyAllianceApplicationHandler::class);

            return $collection;
        });
    }
}
