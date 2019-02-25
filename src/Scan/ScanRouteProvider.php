<?php

declare(strict_types=1);

namespace GC\Scan;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class ScanRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        // display scan
        $collection->get('/{locale}/{universe}/scan/{scanId}', function(ContainerInterface $container) {

        }, 'scan');

        // sector scan
        $collection->post('/{locale}/{universe}/scan/{playerId}/sector', function(ContainerInterface $container) {

        }, 'scan.sector');

        // mili scan
        $collection->post('/{locale}/{universe}/scan/{playerId}/unit', function(ContainerInterface $container) {

        }, 'scan.unit');

        // military scan
        $collection->post('/{locale}/{universe}/scan/{playerId}/military', function(ContainerInterface $container) {

        }, 'scan.military');

        // defense scan
        $collection->post('/{locale}/{universe}/scan/{playerId}/defense', function(ContainerInterface $container) {

        }, 'scan.defense');

        // news scan
        $collection->post('/{locale}/{universe}/scan/{playerId}/news', function(ContainerInterface $container) {

        }, 'scan.news');
    }
}