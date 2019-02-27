<?php

declare(strict_types=1);

namespace GC\App\Routing\Route;

use Inferno\Routing\Route\Route;
use Inferno\Routing\Route\RouteCollection as InfernoRouteCollection;
use Inferno\Routing\Route\RouteInterface;

class RouteCollection extends InfernoRouteCollection
{
    /**
     * @param string[] $methods
     * @param string $path
     * @param mixed $handler
     * @param string|null $name
     *
     * @return \Inferno\Routing\Route\RouteInterface
     */
    public function createRoute(array $methods, string $path, $handler, ?string $name = null): RouteInterface
    {
        return new Route(
            $methods,
            $path,
            $handler,
            $this->resolveRouteName($handler, $name)
        );
    }

    /**
     * @param mixed $handler
     * @param string $name
     * @return string|null
     */
    protected function resolveRouteName($handler, ?string $name): ?string
    {
        if ($name === null
            && \is_string($handler)
            && class_exists($handler)
            && defined("$handler::NAME"))
        {
            $name = constant($handler . '::NAME');
        }

        return $name;
    }
}