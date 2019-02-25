<?php

declare(strict_types=1);

namespace GC\Alliance;

use GC\Alliance\Handler\AllianceApplicationApproveHandler;
use GC\Alliance\Handler\AllianceApplicationsHandler;
use GC\Alliance\Handler\AllianceCreateHandler;
use GC\Alliance\Handler\AllianceCreateSaveHandler;
use GC\Alliance\Handler\AllianceEditHandler;
use GC\Alliance\Handler\AllianceEditSaveHandler;
use GC\Alliance\Handler\AllianceMembersHandler;
use GC\Alliance\Handler\AlliancePublicOverviewHandler;
use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;

final class AllianceRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        $collection->get('/{locale}/{universe}/alliance/{allianceId}', AlliancePublicOverviewHandler::NAME);
        $collection->get('/{locale}/{universe}/alliance/edit', AllianceEditHandler::class);
        $collection->get('/{locale}/{universe}/alliance/create', AllianceCreateHandler::class);
        $collection->get('/{locale}/{universe}/alliance/applications', AllianceApplicationsHandler::class);
        $collection->get('/{locale}/{universe}/alliance/members', AllianceMembersHandler::class);

        $collection->post('/{locale}/{universe}/alliance/save', AllianceEditSaveHandler::class);
        $collection->post('/{locale}/{universe}/alliance/create', AllianceCreateSaveHandler::class);
        $collection->post('/{locale}/{universe}/alliance/application/approve', AllianceApplicationApproveHandler::class);
    }
}