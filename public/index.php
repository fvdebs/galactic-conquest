<?php

$baseDir = __DIR__ . '/..';

require_once $baseDir . '/vendor/autoload.php';

use Inferno\Config\Loader\DirectoryConfigLoader;
use Inferno\Application\Boot\BootLoader;
use Inferno\Config\Config;
use Inferno\Dependency\Container\Container;
use Inferno\Application\Application;

$container = new Container();
$config = new Config($baseDir, (new DirectoryConfigLoader($baseDir . '/config'))->load());
$bootLoader = new BootLoader($config, $container);

$app = new Application($bootLoader);
$app->boot()
    ->run($container->get(\Inferno\HttpFoundation\Kernel\HttpKernel::class))
    ->terminate();
