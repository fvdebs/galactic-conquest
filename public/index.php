<?php

require_once  __DIR__ . '/../vendor/autoload.php';

use Inferno\Application\ApplicationConstants;
use Inferno\Application\ApplicationFactory;
use Inferno\HttpFoundation\Kernel\HttpKernel;

$app = ApplicationFactory::createDefaultApplication();
$app->boot(ApplicationConstants::APPLICATION_BOOTSTRAPPER);
$app->run(HttpKernel::class);
$app->terminate();