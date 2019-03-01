<?php

declare(strict_types=1);

namespace GC\App\Middleware;

use GC\Home\Handler\HomeHandler;
use GC\Player\Model\Player;
use GC\Universe\Handler\UniverseSelectHandler;
use GC\Universe\Model\Universe;
use GC\User\Model\User;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Routing\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthorizationUniverseMiddleware implements MiddlewareInterface
{
    /**
     * @var \Inferno\Http\Response\ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var \Inferno\Routing\Router\RouterInterface
     */
    private $router;

    /**
     * @param \Inferno\Http\Response\ResponseFactoryInterface $responseFactory
     * @param \Inferno\Routing\Router\RouterInterface $router
     */
    public function __construct(ResponseFactoryInterface $responseFactory, RouterInterface $router)
    {
        $this->responseFactory = $responseFactory;
        $this->router = $router;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @throws \Inferno\Routing\Exception\ResourceNotFoundException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! $this->isUniverseNameSlugGiven($request)) {
            return $handler->handle($request);
        }

        if (! $this->isCurrentUserGiven($request) || ! $this->isCurrentUniverseGiven($request)) {
            return $this->createRedirect(HomeHandler::NAME);
        }

        if (! $this->isCurrentPlayerGiven($request)) {
            return $this->createRedirect(UniverseSelectHandler::NAME);
        }

        return $handler->handle($request);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return bool
     */
    private function isUniverseNameSlugGiven(ServerRequestInterface $request): bool
    {
        return $request->getAttribute('universe') !== null;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return bool
     */
    private function isCurrentUserGiven(ServerRequestInterface $request): bool
    {
        return $request->getAttribute(SetCurrentUserMiddleware::REQUEST_ATTRIBUTE_CURRENT_USER) instanceof User;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return bool
     */
    private function isCurrentUniverseGiven(ServerRequestInterface $request): bool
    {
        return $request->getAttribute(SetCurrentUniverseMiddleware::REQUEST_ATTRIBUTE_CURRENT_UNIVERSE) instanceof Universe;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return bool
     */
    private function isCurrentPlayerGiven(ServerRequestInterface $request): bool
    {
        return $request->getAttribute(SetCurrentPlayerMiddleware::REQUEST_ATTRIBUTE_CURRENT_PLAYER) instanceof Player;
    }

    /**
     * @param string $name
     *
     * @throws \Inferno\Routing\Exception\ResourceNotFoundException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function createRedirect(string $name): ResponseInterface
    {
        return $this->responseFactory->createFromContent(
            $this->router->generate($name)
        );
    }
}