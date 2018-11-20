<?php

declare(strict_types=1);

use Inferno\Application\ApplicationConstants;
use Inferno\Locale\LocaleConstants;
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

    /*
    |--------------------------------------------------------------------------
    | Locale (route prefix => iso locale) @todo
    |--------------------------------------------------------------------------
    */
    LocaleConstants::LOCALE_FALLBACK => 'en_EN',
    LocaleConstants::LOCALE_AVAILABLE => [
        'en' => 'en_EN',
        'de' => 'de_DE',
    ],
];
