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
        \GC\Home\HomeServiceProvider::class,
        \GC\User\UserServiceProvider::class,
    ],
];
