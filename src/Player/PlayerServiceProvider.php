<?php

declare(strict_types=1);

namespace GC\Player;

use Doctrine\ORM\EntityManager;
use GC\Player\Handler\PlayerCombatReportExternalHandler;
use GC\Player\Handler\PlayerCombatReportHandler;
use GC\Player\Handler\PlayerCombatReportListHandler;
use GC\Player\Handler\PlayerCreateHandler;
use GC\Player\Handler\PlayerExtractorBuildHandler;
use GC\Player\Handler\PlayerFleetBuildHandler;
use GC\Player\Handler\PlayerFleetMissionHandler;
use GC\Player\Handler\PlayerFleetOverviewHandler;
use GC\Player\Handler\PlayerOverviewHandler;
use GC\Player\Handler\PlayerResourcesHandler;
use GC\Player\Handler\PlayerScanBlockerBuildHandler;
use GC\Player\Handler\PlayerScanListHandler;
use GC\Player\Handler\PlayerScanRelayBuildHandler;
use GC\Player\Handler\PlayerTechnologyBuildHandler;
use GC\Player\Handler\PlayerTechnologyListHandler;
use GC\Player\Handler\PlayerUnitBuildHandler;
use GC\Player\Handler\PlayerUnitListHandler;
use GC\Player\Model\Player;
use GC\Player\Model\PlayerRepository;
use Inferno\Routing\Route\RouteCollection;
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
        $this->providePlayerRoutes($pimple);
        $this->providePlayerRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function providePlayerRoutes(Container $container): void
    {
        $container->extend(RouteCollection::class, function(RouteCollection $collection, Container $container)
        {
            $collection->get('/{locale}/report/{combatReportExternalId}', PlayerCombatReportExternalHandler::class)->addAttribute('public', true);

            $collection->get('/{locale}/{universe}/player/overview', PlayerOverviewHandler::class);
            $collection->get('/{locale}/{universe}/player/technology', PlayerTechnologyListHandler::class);
            $collection->get('/{locale}/{universe}/player/unit', PlayerUnitListHandler::class);
            $collection->get('/{locale}/{universe}/player/resources', PlayerResourcesHandler::class);
            $collection->get('/{locale}/{universe}/player/scan', PlayerScanListHandler::class);
            $collection->get('/{locale}/{universe}/player/fleet', PlayerFleetOverviewHandler::class);
            $collection->get('/{locale}/{universe}/player/report/{combatReportId}', PlayerCombatReportHandler::class);
            $collection->get('/{locale}/{universe}/player/reports', PlayerCombatReportListHandler::class);

            $collection->post('/{locale}/{universe}/player/technology', PlayerTechnologyBuildHandler::class);
            $collection->post('/{locale}/{universe}/player/unit', PlayerUnitBuildHandler::class);
            $collection->post('/{locale}/{universe}/player/extractor', PlayerExtractorBuildHandler::class);
            $collection->post('/{locale}/{universe}/player/scan-relay', PlayerScanRelayBuildHandler::class);
            $collection->post('/{locale}/{universe}/player/scan-blocker', PlayerScanBlockerBuildHandler::class);
            $collection->post('/{locale}/{universe}/player/create', PlayerCreateHandler::class);
            $collection->post('/{locale}/{universe}/player/fleet', PlayerFleetBuildHandler::class);
            $collection->post('/{locale}/{universe}/player/fleet/mission', PlayerFleetMissionHandler::class);

            return $collection;
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
            return $container->offsetGet(EntityManager::class)->getRepository(Player::class);
        });
    }
}