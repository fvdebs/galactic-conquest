<?php

declare(strict_types=1);

namespace GC\Combat;

use GC\Combat\Handler\CombatReportExternalHandler;
use GC\Combat\Handler\CombatReportHandler;
use GC\Combat\Handler\CombatReportListHandler;
use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;

final class CombatRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        $collection->get('/{locale}/combat/{combatReportExternalId}', CombatReportExternalHandler::class);
        $collection->get('/{locale}/{universe}/combat/report/{combatReportId}', CombatReportHandler::class);
        $collection->get('/{locale}/{universe}/combat/reports', CombatReportListHandler::class);
    }
}