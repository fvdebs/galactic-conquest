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
            SingletonContainer::getContainer()->offsetGet('renderer')->render($path, $placeholders),
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
        $routerChain = SingletonContainer::getContainer()->offsetGet(RouterChain::class);
        $uriFactory = SingletonContainer::getContainer()->offsetGet('uri-factory');

        return $this->getResponseFactory()->createFromContent(
            $uriFactory->createUri($routerChain->generate($name, $parameters)),
            $code,
            $headers
        );
    }
}