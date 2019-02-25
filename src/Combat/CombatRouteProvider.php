<?php

declare(strict_types=1);

namespace GC\Combat;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class CombatRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        // display external combat report
        $collection->get('/{locale}/combat/{combatReportExternalId}', function(ContainerInterface $container) {

        }, 'combat.report.external');

        // display report
        $collection->get('/{locale}/{universe}/combat/report/{combatReportId}', function(ContainerInterface $container) {

        }, 'combat.report');

        // list of last combat reports
        $collection->get('/{locale}/{universe}/combat/reports', function(ContainerInterface $container) {

        }, 'combat.reports');
    }
}