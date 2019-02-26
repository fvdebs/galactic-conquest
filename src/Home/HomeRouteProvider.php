<?php

declare(strict_types=1);

namespace GC\Home;

use GC\Home\Handler\HomeHandler;
use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;

final class HomeRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        $collection->get('/{locale}', HomeHandler::class);
    }
}