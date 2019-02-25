<?php

declare(strict_types=1);

namespace GC\Galaxy;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class GalaxyRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        // display galaxy by number
        $collection->get('/{locale}/{universe}/galaxy/{number}', function(ContainerInterface $container) {

        }, 'galaxy.browse');

        // edit galaxy
        $collection->get('/{locale}/{universe}/galaxy/edit', function(ContainerInterface $container) {

        }, 'galaxy.edit');

        // edit galaxy
        $collection->post('/{locale}/{universe}/galaxy/edit', function(ContainerInterface $container) {

        }, 'galaxy.edit.post');

        // galaxy overview
        $collection->get('/{locale}/{universe}/galaxy', function(ContainerInterface $container) {

        }, 'galaxy.overview');

        // tactic
        $collection->get('/{locale}/{universe}/galaxy/tactic', function(ContainerInterface $container) {

        }, 'galaxy.tactic');

        // display player information for own galaxy
        $collection->get('/{locale}/{universe}/galaxy/tactic/{position}', function(ContainerInterface $container) {

        }, 'galaxy.playerinfo');

        // displays technologies of galaxy
        $collection->get('/{locale}/{universe}/galaxy/technology', function(ContainerInterface $container) {

        }, 'galaxy.technology');

        // builds technology for galaxy
        $collection->post('/{locale}/{universe}/galaxy/technology', function(ContainerInterface $container) {

        }, 'galaxy.technology.post');


        $collection->get('/{locale}/{universe}/galaxy/application', function(ContainerInterface $container) {

        }, 'galaxy.application');

        // galaxy application for alliance POST
        $collection->post('/{locale}/{universe}/galaxy/application', function(ContainerInterface $container) {

        }, 'galaxy.application.save');
    }
}