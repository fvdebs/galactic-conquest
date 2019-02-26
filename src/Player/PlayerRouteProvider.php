<?php

declare(strict_types=1);

namespace GC\Player;

use GC\Player\Handler\PlayerCreateHandler;
use GC\Player\Handler\PlayerExtractorBuildHandler;
use GC\Player\Handler\PlayerFleetBuildHandler;
use GC\Player\Handler\PlayerFleetMissionHandler;
use GC\Player\Handler\PlayerFleetOverviewHandler;
use GC\Player\Handler\PlayerOverviewHandler;
use GC\Player\Handler\PlayerResourcesHandler;
use GC\Player\Handler\PlayerScanBlockerBuildHandler;
use GC\Player\Handler\PlayerScanRelayBuildHandler;
use GC\Player\Handler\PlayerScansHandler;
use GC\Player\Handler\PlayerTechnologyBuildHandler;
use GC\Player\Handler\PlayerTechnologyListHandler;
use GC\Player\Handler\PlayerUnitBuildHandler;
use GC\Player\Handler\PlayerUnitListHandler;
use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;

final class PlayerRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        $collection->get('/{locale}/{universe}/player/overview', PlayerOverviewHandler::class);
        $collection->get('/{locale}/{universe}/player/technology', PlayerTechnologyListHandler::class);
        $collection->get('/{locale}/{universe}/player/unit', PlayerUnitListHandler::class);
        $collection->get('/{locale}/{universe}/player/resources', PlayerResourcesHandler::class);
        $collection->get('/{locale}/{universe}/player/scan', PlayerScansHandler::class);
        $collection->get('/{locale}/{universe}/player/fleet', PlayerFleetOverviewHandler::class);

        $collection->post('/{locale}/{universe}/player/technology', PlayerTechnologyBuildHandler::class);
        $collection->post('/{locale}/{universe}/player/unit', PlayerUnitBuildHandler::class);
        $collection->post('/{locale}/{universe}/player/extractor', PlayerExtractorBuildHandler::class);
        $collection->post('/{locale}/{universe}/player/scan-relay', PlayerScanRelayBuildHandler::class);
        $collection->post('/{locale}/{universe}/player/scan-blocker', PlayerScanBlockerBuildHandler::class);
        $collection->post('/{locale}/{universe}/player/create', PlayerCreateHandler::class);
        $collection->post('/{locale}/{universe}/player/fleet', PlayerFleetBuildHandler::class);
        $collection->post('/{locale}/{universe}/player/fleet/mission', PlayerFleetMissionHandler::class);
    }
}