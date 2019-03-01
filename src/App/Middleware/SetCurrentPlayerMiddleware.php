<?php

declare(strict_types=1);

namespace GC\App\Middleware;

use GC\Player\Model\PlayerRepository;
use GC\Universe\Model\Universe;
use GC\User\Model\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SetCurrentPlayerMiddleware implements MiddlewareInterface
{
    public const REQUEST_ATTRIBUTE_CURRENT_PLAYER = '@currentPlayer';

    /**
     * @var \GC\Player\Model\PlayerRepository
     */
    private $playerRepository;

    /**
     * @param \GC\Player\Model\PlayerRepository $playerRepository
     */
    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
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
        $user = $request->getAttribute(SetCurrentUserMiddleware::REQUEST_ATTRIBUTE_CURRENT_USER);
        $universe = $request->getAttribute(SetCurrentUniverseMiddleware::REQUEST_ATTRIBUTE_CURRENT_UNIVERSE);

        if (!($user instanceof User) || !($universe instanceof Universe)) {
            return $handler->handle($request);
        }

        $player = $this->playerRepository->findByUserIdAndUniverseId(
            $user->getUserId(),
            $universe->getUniverseId()
        );

        if ($player === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute(static::REQUEST_ATTRIBUTE_CURRENT_PLAYER, $player);

        return $handler->handle($request);
    }
}