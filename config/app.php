<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Clear Cache Command
    |--------------------------------------------------------------------------
    */
    'cache-dirs' => [
        '%baseDir%/data/cache',
        '%baseDir%/data/logs'
    ],
    'cache-files' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | App Service Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        \GC\App\AppServiceProvider::class,
        \GC\Alliance\AllianceServiceProvider::class,
        \GC\Galaxy\GalaxyServiceProvider::class,
        \GC\Home\HomeServiceProvider::class,
        \GC\Player\PlayerServiceProvider::class,
        \GC\Scan\ScanServiceProvider::class,
        \GC\User\UserServiceProvider::class,
        \GC\Universe\UniverseServiceProvider::class,
    ],
];