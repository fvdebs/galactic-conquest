<?php

declare(strict_types=1);

use Inferno\Application\ApplicationConstants;
use Inferno\Cache\CacheConstants;
use Inferno\Config\ConfigConstants;
use Inferno\DiactorosBridge\DiactorosBridgeConstants;
use Inferno\FastRouteBridge\FastRouteBridgeConstants;
use Inferno\HttpFoundation\HttpFoundationConstants;
use Inferno\Doctrine\DoctrineConstants;
use Inferno\Locale\LocaleConstants;
use Inferno\Maintenance\MaintenanceConstants;
use Inferno\MonologBridge\MonologBridgeConstants;
use Inferno\Routing\RoutingConstants;
use Inferno\Translation\TranslationConstants;
use Inferno\TwigBridge\TwigBridgeConstants;
use Inferno\Error\ErrorConstants;

return [
    /*
    |--------------------------------------------------------------------------
    | Application
    |--------------------------------------------------------------------------
    */
    ConfigConstants::CONFIG_ENVIRONMENT => ConfigConstants::CONFIG_ENVIRONMENT_DEV,
    HttpFoundationConstants::HTTP_FOUNDATION_REQUEST_FACTORY => \Inferno\DiactorosBridge\Request\ServerRequestFactory::class,
    HttpFoundationConstants::HTTP_FOUNDATION_ERROR_RESPONSE_FACTORY => \Inferno\Error\Response\ErrorResponseFactory::class,

    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    */
    ErrorConstants::ERROR_RESPONSE_FACTORY => \Inferno\DiactorosBridge\Response\ResponseFactory::class,

    /*
    |--------------------------------------------------------------------------
    | Maintenance
    |--------------------------------------------------------------------------
    */
    MaintenanceConstants::MAINTENANCE_MODE => false,

    /*
    |--------------------------------------------------------------------------
    | Bootstrapper
    |--------------------------------------------------------------------------
    */
    ApplicationConstants::APPLICATION_BOOTSTRAPPER => [
        \Inferno\Application\Bootstrapper\FactoriesBootstrapper::class,
        \Inferno\Application\Bootstrapper\ServiceProviderBootstrapper::class,
        \Inferno\Routing\Bootstrapper\RouterChainBootstrapper::class,
        \Inferno\Routing\Bootstrapper\RouteProviderBootstrapper::class,
        \Inferno\HttpFoundation\Bootstrapper\MiddlewareBootstrapper::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Factories
    |--------------------------------------------------------------------------
    */
    ApplicationConstants::APPLICATION_FACTORIES => [
        \Inferno\HttpFoundation\HttpFoundationFactory::class,
        \Inferno\DiactorosBridge\DiactorosBridgeFactory::class,
        \Inferno\Cache\CacheFactory::class,
        \Inferno\Doctrine\DoctrineFactory::class,
        \Inferno\Event\EventFactory::class,
        \Inferno\Filesystem\FilesystemFactory::class,
        \Inferno\Locale\LocaleFactory::class,
        \Inferno\MonologBridge\MonologBridgeFactory::class,
        \Inferno\Session\SessionFactory::class,
        \Inferno\Translation\TranslationFactory::class,
        \Inferno\TwigBridge\TwigBridgeFactory::class,
        \Inferno\Maintenance\MaintenanceFactory::class,
        \Inferno\Routing\RoutingFactory::class,
        \Inferno\FastRouteBridge\FastRouteBridgeFactory::class,
        \Inferno\Error\ErrorFactory::class,
        \GC\Home\HomeFactory::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Provider
    |--------------------------------------------------------------------------
    */
    ApplicationConstants::APPLICATION_SERVICE_PROVIDER => [
        \Inferno\HttpFoundation\Service\HttpFoundationServiceProvider::class,
        \Inferno\DiactorosBridge\Service\DiactorosBridgeServiceProvider::class,
        \Inferno\Cache\Service\CacheServiceProvider::class,
        \Inferno\Doctrine\Service\DoctrineServiceProvider::class,
        \Inferno\Event\Service\EventServiceProvider::class,
        \Inferno\Filesystem\Service\FilesystemServiceProvider::class,
        \Inferno\Locale\Service\LocaleServiceProvider::class,
        \Inferno\MonologBridge\Service\MonologBridgeServiceProvider::class,
        \Inferno\Session\Service\SessionServiceProvider::class,
        \Inferno\Translation\Service\TranslationServiceProvider::class,
        \Inferno\Routing\Service\RoutingServiceProvider::class,
        \Inferno\TwigBridge\Service\TwigBridgeServiceProvider::class,
        \Inferno\Maintenance\Service\MaintenanceServiceProvider::class,
        \Inferno\FastRouteBridge\Service\FastRouteBridgeServiceProvider::class,
        \Inferno\Error\Service\ErrorServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */
    HttpFoundationConstants::HTTP_FOUNDATION_MIDDLEWARES => [
        \Inferno\Error\Middleware\WhoopsMiddleware::class,
        \Inferno\Maintenance\Middleware\MaintenanceMiddleware::class,
        \Inferno\Locale\Middleware\LocaleMiddleware::class,
        \Inferno\Translation\Middleware\SetTranslatorLocaleMiddleware::class,
        \Inferno\Session\Middleware\StartSessionMiddleware::class,
        \Inferno\Routing\Middleware\DispatcherMiddleware::class,
    ],

    /*
   |--------------------------------------------------------------------------
   | Router Chain
   |--------------------------------------------------------------------------
   */
    RoutingConstants::ROUTING_ROUTER_CHAIN => [
        \Inferno\FastRouteBridge\Router\FastRouter::class
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
    | Doctrine
    |--------------------------------------------------------------------------
    */
    DoctrineConstants::DOCTRINE_PROXY_DIR => '%baseDir%/data/persistence/proxies',
    DoctrineConstants::DOCTRINE_FIXTURE_DIR => '%baseDir%/data/persistence/fixtures',
    DoctrineConstants::DOCTRINE_ENTITY_DIR => ['%baseDir%/src'],
    DoctrineConstants::DOCTRINE_IS_DEV => false,
    DoctrineConstants::DOCTRINE_CONFIG => [
        'host' => 'localhost',
        'user' => 'dev',
        'password' => 'secret',
        'dbname' => 'demo',
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

    /*
   |--------------------------------------------------------------------------
   | Translation
   |--------------------------------------------------------------------------
   */
    TranslationConstants::TRANSLATION_DIR => '%baseDir%/data/translations',

    /*
    |--------------------------------------------------------------------------
    | DiactorosBridge
    |--------------------------------------------------------------------------
    */
    DiactorosBridgeConstants::DIACTOROS_BRIDGE_RESPONSE_FACTORY => \Inferno\DiactorosBridge\Response\ResponseFactory::class,

    /*
    |--------------------------------------------------------------------------
    | TwigBridge
    |--------------------------------------------------------------------------
    */
    TwigBridgeConstants::TWIG_RESPONSE_FACTORY_CONTAINER_KEY => DiactorosBridgeConstants::DIACTOROS_BRIDGE_RESPONSE_FACTORY,
    TwigBridgeConstants::TWIG_BRIDGE_CACHE_DIR => '%baseDir%/data/cache/twig',
    TwigBridgeConstants::TWIG_BRIDGE_TEMPLATE_PATHS => ['{{FactoryPath}}/Theme/{{Theme}}'],
    TwigBridgeConstants::TWIG_BRIDGE_THEME_DIR => 'default',
    TwigBridgeConstants::TWIG_BRIDGE_NAMESPACED_TPL_FACTORY_KEY => ApplicationConstants::APPLICATION_FACTORIES,

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */
    FastRouteBridgeConstants::FAST_ROUTE_BRIDGE_CACHE_FILE => '%baseDir%/data/cache/route/route.cache.php',
    RoutingConstants::ROUTING_INVOCATION_STRATEGY => \Inferno\Routing\Strategy\RequestContainerStrategy::NAME,
    RoutingConstants::ROUTING_ROUTE_LOADER => [
        \Inferno\Routing\Loader\RouteCollectionLoader::class,
        // PhpFileRouteLoader::class,
    ],
    RoutingConstants::ROUTING_ROUTE_LOADER_PHP_FILEPATH => [
        '%baseDir%/config/routes.php',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */
    CacheConstants::CACHE_REDIS_HOST => 'localhost',
    CacheConstants::CACHE_REDIS_PORT => 6379,
    CacheConstants::CACHE_DIRECTORIES => ['%baseDir%/data/cache', '%baseDir%/data/logs'],
    CacheConstants::CACHE_FILES => [],

    /*
   |--------------------------------------------------------------------------
   | MonologBridge
   |--------------------------------------------------------------------------
   */
    MonologBridgeConstants::MONOLOG_BRIDGE_APP_EXCEPTION_LOG => '%baseDir%/data/logs/app_%environment%.log',
    MonologBridgeConstants::MONOLOG_BRIDGE_LOG_FORMAT => "[%datetime%] [%level_name%] %message% %context% %extra%\n",
    MonologBridgeConstants::MONOLOG_BRIDGE_EXTRA_FIELDS => [
        'url' => 'REQUEST_URI',
        'ip' => 'REMOTE_ADDR',
        'method' => 'REQUEST_METHOD',
        'referrer' => 'HTTP_REFERER'
    ],
];
