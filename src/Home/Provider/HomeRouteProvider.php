<?php

declare(strict_types=1);

namespace Acme\Home;

use Inferno\Routing\Route\RouteCollection;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollection $collection
     *
     * @return void
     */
    public function provide(RouteCollection $collection): void
    {
        $collection->get('/', function(ServerRequestInterface $request, ContainerInterface $container) {
            return $container->get(HomeFactory::class)->createHomeHandler()->handle($request);
        })->setName('home');
    }
}
