<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerScanBlockerBuildHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'player.scan.blocker.build';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Player/playerScanBlockerBuild.twig');
    }
}