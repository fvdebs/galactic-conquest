<?php
echo 'hello world'; exit;

$baseDir = __DIR__ . '/..';

require_once $baseDir . '/vendor/autoload.php';

use Inferno\Config\Loader\DirectoryConfigLoader;
use Inferno\Application\Boot\BootLoader;
use Inferno\HttpFoundation\HttpFoundationFactory;
use Inferno\Config\Config;
use Inferno\Dependency\Container\Container;

$bootLoader = new BootLoader(
    new Config($baseDir, (new DirectoryConfigLoader($baseDir . '/configs'))->load()),
    new Container()
);

$factory = new HttpFoundationFactory();

$app = $factory->createHttpApplication(
    $bootLoader,
    new \Inferno\HttpFoundation\Handler\RequestHandlerRunner()
);


$app->boot()
    ->run()
    ->terminate();
