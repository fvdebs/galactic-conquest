<?php

declare(strict_types=1);

namespace GC\App\Middleware;

use GC\Universe\Model\UniverseRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SetCurrentUniverseMiddleware implements MiddlewareInterface
{
    public const REQUEST_ATTRIBUTE_CURRENT_UNIVERSE = '@currentUniverse';
    public const UNIVERSE_SLUG_NAME = 'universe';

    /**
     * @var \GC\Universe\Model\UniverseRepository
     */
    private $universeRepository;

    /**
     * @param \GC\Universe\Model\UniverseRepository $universeRepository
     */
    public function __construct(UniverseRepository $universeRepository)
    {
        $this->universeRepository = $universeRepository;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $universe = $this->universeRepository->findByName(
            $request->getAttribute(static::UNIVERSE_SLUG_NAME)
        );

        if ($universe === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute(static::REQUEST_ATTRIBUTE_CURRENT_UNIVERSE, $universe);

        return $handler->handle($request);
    }
}