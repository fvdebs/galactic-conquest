<?php

declare(strict_types=1);

namespace GC\Player;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class PlayerRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        // creates a player for current user and universe by name
        $collection->post('/{locale}/{universe}/player/create', function(ContainerInterface $container) {

        }, 'player.create.post');

        // overview for current player
        $collection->get('/{locale}/{universe}/player/overview', function(ContainerInterface $container) {

        }, 'player.overview');


        // technology list
        $collection->get('/{locale}/{universe}/player/technology', function(ContainerInterface $container) {

        }, 'player.technology');

        // technology build
        $collection->post('/{locale}/{universe}/player/technology', function(ContainerInterface $container) {

        }, 'player.technology.post');


        // unit list
        $collection->get('/{locale}/{universe}/player/unit', function(ContainerInterface $container) {

        }, 'player.unit');

        // unit build
        $collection->post('/{locale}/{universe}/player/unit', function(ContainerInterface $container) {

        }, 'player.unit.post');


        // player resource overview
        $collection->get('/{locale}/{universe}/player/resource', function(ContainerInterface $container) {

        }, 'player.resource');

        // build extractor
        $collection->post('/{locale}/{universe}/player/extractor', function(ContainerInterface $container) {

        }, 'player.extractor.post');


        // scan overview
        $collection->get('/{locale}/{universe}/player/scan', function(ContainerInterface $container) {

        }, 'player.scan.relay.post');

        // build scan relays
        $collection->get('/{locale}/{universe}/player/scan-relay', function(ContainerInterface $container) {

        }, 'player.scan.relay.post');

        // build scan blocker
        $collection->post('/{locale}/{universe}/player/scan-blocker', function(ContainerInterface $container) {

        }, 'player.scan.blocker.post');


        // player fleet overview
        $collection->get('/{locale}/{universe}/player/fleet', function(ContainerInterface $container) {

        }, 'player.fleet');

        // rearrange player fleets
        $collection->post('/{locale}/{universe}/player/fleet', function(ContainerInterface $container) {

        }, 'player.fleet.post');

        // send player fleet to mission
        $collection->post('/{locale}/{universe}/player/fleet/mission', function(ContainerInterface $container) {

        }, 'player.fleet.mission.post');
    }
}