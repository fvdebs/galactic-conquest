<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Inferno\Application\Application;
use Inferno\HttpFoundation\Kernel\HttpKernel;

$app = new Application( __DIR__ . '/..', new \Inferno\Application\Dependency\SingletonContainer());
$app->addDefaultBootstrapper();
$app->boot();
$app->run($app->getContainer()->offsetGet(HttpKernel::class));
$app->terminate();
