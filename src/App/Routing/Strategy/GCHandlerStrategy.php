<?php

declare(strict_types=1);

namespace GC\App\Routing\Strategy;

use Inferno\Routing\Route\RouteInterface;
use Inferno\Routing\Strategy\ContainerHandlerStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GCHandlerStrategy extends ContainerHandlerStrategy
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Inferno\Routing\Route\RouteInterface $route
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function invoke(ServerRequestInterface $request, RouteInterface $route): ResponseInterface
    {
        $handler = $route->getHandler();
        if (\is_string($handler) && \class_exists($handler)) {
            return (new $handler())->handle($request);
        }

        return parent::invoke($request, $route);
    }
}