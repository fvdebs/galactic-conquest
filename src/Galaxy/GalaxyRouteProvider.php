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
use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;

final class GalaxyRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
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
    }
}