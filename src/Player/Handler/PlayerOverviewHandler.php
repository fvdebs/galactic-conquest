<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerOverviewHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'player.overview';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Player/playerOverview.twig');
    }
}