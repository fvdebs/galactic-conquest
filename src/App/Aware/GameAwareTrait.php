<?php

namespace GC\App\Aware;

use GC\App\Middleware\SetCurrentPlayerMiddleware;
use GC\App\Middleware\SetCurrentUniverseMiddleware;
use GC\App\Middleware\SetCurrentUserMiddleware;
use GC\Player\Model\Player;
use GC\Universe\Model\Universe;
use GC\User\Model\User;
use Psr\Http\Message\ServerRequestInterface;

trait GameAwareTrait
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \GC\Player\Model\Player
     */
    protected function getCurrentPlayer(ServerRequestInterface $request): Player
    {
        return $request->getAttribute(SetCurrentPlayerMiddleware::REQUEST_ATTRIBUTE_CURRENT_PLAYER);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \GC\Universe\Model\Universe
     */
    protected function getCurrentUniverse(ServerRequestInterface $request): Universe
    {
        return $request->getAttribute(SetCurrentUniverseMiddleware::REQUEST_ATTRIBUTE_CURRENT_UNIVERSE);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \GC\User\Model\User
     */
    protected function getCurrentUser(ServerRequestInterface $request): User
    {
        return $request->getAttribute(SetCurrentUserMiddleware::REQUEST_ATTRIBUTE_CURRENT_USER);
    }
}
