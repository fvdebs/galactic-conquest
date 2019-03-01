<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerUnitBuildHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'player.unit.build';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Player/playerUnitBuild.twig');
    }
}