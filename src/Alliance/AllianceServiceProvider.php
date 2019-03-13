<?php

declare(strict_types=1);

namespace GC\Alliance;

use GC\Alliance\Handler\AllianceApplicationApproveHandler;
use GC\Alliance\Handler\AllianceCreateSaveHandler;
use GC\Alliance\Handler\AllianceEconomyHandler;
use GC\Alliance\Handler\AllianceEditHandler;
use GC\Alliance\Handler\AllianceEditSaveHandler;
use GC\Alliance\Handler\AllianceMembersHandler;
use GC\Alliance\Handler\AlliancePublicOverviewHandler;
use Inferno\Routing\Route\RouteCollection;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class AllianceServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideAllianceRoutes($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideAllianceRoutes(Container $container): void
    {
        $container->extend(RouteCollection::class, function (RouteCollection $collection, Container $container)
        {
            $collection->get('/{locale}/{universe}/alliance', AllianceEditHandler::class);
            $collection->get('/{locale}/{universe}/alliance/members', AllianceMembersHandler::class);
            $collection->get('/{locale}/{universe}/alliance/economy', AllianceEconomyHandler::class);

            $collection->get('/{locale}/{universe}/alliance/{allianceId}', AlliancePublicOverviewHandler::class);

            $collection->post('/{locale}/{universe}/alliance/save', AllianceEditSaveHandler::class);
            $collection->post('/{locale}/{universe}/alliance/create', AllianceCreateSaveHandler::class);
            $collection->post('/{locale}/{universe}/alliance/application/approve', AllianceApplicationApproveHandler::class);

            return $collection;
        });
    }
}