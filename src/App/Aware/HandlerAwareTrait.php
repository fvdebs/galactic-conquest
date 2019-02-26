<?php

namespace GC\App\Aware;

use GC\App\Dependency\SingletonContainer;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Renderer\RendererInterface;
use Inferno\Routing\Router\RouterChain;
use Inferno\Routing\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;

trait HandlerAwareTrait
{
    /**
     * @return \Inferno\Http\Response\ResponseFactoryInterface
     */
    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return SingletonContainer::getContainer()->offsetGet('response-factory');
    }

    /**
     * @return \Psr\Http\Message\UriFactoryInterface
     */
    protected function getUriFactory(): UriFactoryInterface
    {
        return SingletonContainer::getContainer()->offsetGet('uri-factory');
    }

    /**
     * @return \Inferno\Renderer\RendererInterface
     */
    protected function getRenderer(): RendererInterface
    {
        return SingletonContainer::getContainer()->offsetGet('renderer');
    }

    /**
     * @return \Inferno\Routing\Router\RouterInterface
     */
    protected function getRouterChain(): RouterInterface
    {
        return SingletonContainer::getContainer()->offsetGet(RouterChain::class);
    }

    /**
     * @param string $path
     * @param string[] $placeholders
     * @param int $code
     * @param string[] $headers
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function renderResponse(string $path, array $placeholders = [], int $code = 200, array $headers = []): ResponseInterface
    {
        return $this->getResponseFactory()->createFromContent(
            $this->getRenderer()->render($path, $placeholders),
            $code,
            $headers
        );
    }

    /**
     * @param string $name
     * @param string[] $parameters
     * @param int $code
     * @param string[] $headers
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function redirectRoute(string $name, array $parameters = [], int $code = 200, array $headers = []): ResponseInterface
    {
        return $this->getResponseFactory()->createFromContent(
            $this->getUriFactory()->createUri($this->getRouterChain()->generate($name, $parameters)),
            $code,
            $headers
        );
    }
}