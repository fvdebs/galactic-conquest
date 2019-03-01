<?php

declare(strict_types=1);

namespace GC\App\Middleware;

use GC\Player\Model\Player;
use GC\Universe\Model\Universe;
use GC\User\Model\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig_Environment;

final class SetTwigGlobalsMiddleware implements MiddlewareInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $currentUser = $this->getCurrentUser($request);
        if ($currentUser) {
            $this->twig->addGlobal('currentUserId', $currentUser->getUserId());
            $this->twig->addGlobal('currentUserName', $currentUser->getName());
            $this->twig->addGlobal('currentUser', $currentUser);
            $this->twig->addGlobal('isLoggedIn', true);
        } else {
            $this->twig->addGlobal('isLoggedIn', false);
        }

        $universe = $this->getCurrentUniverse($request);
        if ($universe) {
            $this->twig->addGlobal('currentUniverseId', $universe->getUniverseId());
            $this->twig->addGlobal('currentUniverseName', $universe->getUniverseId());
            $this->twig->addGlobal('currentUniverse', $universe);
        }

        $player = $this->getCurrentPlayer($request);
        if ($player) {
            $this->twig->addGlobal('currentPlayerId', $player->getPlayerId());
            $this->twig->addGlobal('currentPlayer', $player);
        }

        return $handler->handle($request);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \GC\User\Model\User|null
     */
    private function getCurrentUser(ServerRequestInterface $request): ?User
    {
        return $request->getAttribute(SetCurrentUserMiddleware::REQUEST_ATTRIBUTE_CURRENT_USER);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \GC\Universe\Model\Universe|null
     */
    private function getCurrentUniverse(ServerRequestInterface $request): ?Universe
    {
        return $request->getAttribute(SetCurrentUniverseMiddleware::REQUEST_ATTRIBUTE_CURRENT_UNIVERSE);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \GC\Player\Model\Player|null
     */
    private function getCurrentPlayer(ServerRequestInterface $request): ?Player
    {
        return $request->getAttribute(SetCurrentPlayerMiddleware::REQUEST_ATTRIBUTE_CURRENT_PLAYER);
    }
}