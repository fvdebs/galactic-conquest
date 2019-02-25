<?php

namespace GC\App\Aware;

use GC\App\Dependency\SingletonContainer;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Renderer\RendererInterface;
use Inferno\Routing\UrlGenerator\UrlGenerator;
use Inferno\Routing\UrlGenerator\UrlGeneratorInterface;
use Pimple\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;

trait HandlerAwareTrait
{
    /**
     * @return \Pimple\Container
     */
    private function getContainer(): Container
    {
        return SingletonContainer::getContainer();
    }

    /**
     * @return \Inferno\Http\Response\ResponseFactoryInterface
     */
    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->getContainer()->offsetGet('response-factory');
    }

    /**
     * @return \Psr\Http\Message\UriFactoryInterface
     */
    protected function getUriFactory(): UriFactoryInterface
    {
        return $this->getContainer()->offsetGet('uri-factory');
    }

    /**
     * @return \Inferno\Renderer\RendererInterface
     */
    protected function getRenderer(): RendererInterface
    {
        return $this->getContainer()->offsetGet('renderer');
    }

    /**
     * @return \Inferno\Routing\UrlGenerator\UrlGeneratorInterface
     */
    protected function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->getContainer()->offsetGet(UrlGenerator::class);
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
            $this->getUriFactory()->createUri($this->getUrlGenerator()->generate($name, $parameters)),
            $code,
            $headers
        );
    }
}