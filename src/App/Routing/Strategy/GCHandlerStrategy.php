<?php

declare(strict_types=1);

namespace GC\App\Routing\Strategy;

use Inferno\Routing\Route\RouteInterface;
use Inferno\Routing\Strategy\StrategyInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GCHandlerStrategy implements StrategyInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Inferno\Routing\Route\RouteInterface $route
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function invoke(ServerRequestInterface $request, RouteInterface $route): ResponseInterface
    {
        $handler = $route->getHandler();
        if (is_string($handler) && class_exists($handler)) {
            $handler = new $handler();
        } else {
            /** @var \Psr\Http\Server\RequestHandlerInterface $handler */
            $handler = ($route->getHandler())($this->container);
        }

        return $handler->handle($request);
    }
}