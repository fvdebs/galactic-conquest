<?php declare(strict_types=1);

use Inferno\Application\ApplicationConstants;
use Inferno\Routing\RoutingConstants;
 
return [
    /*
    |--------------------------------------------------------------------------
    | Factories
    |--------------------------------------------------------------------------
    */
    ApplicationConstants::APPLICATION_FACTORIES => [
        \GC\Home\HomeFactory::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Provider
    |--------------------------------------------------------------------------
    */
    RoutingConstants::ROUTING_ROUTE_PROVIDER => [
        \GC\Home\Provider\HomeRouteProvider::class
    ],
];
