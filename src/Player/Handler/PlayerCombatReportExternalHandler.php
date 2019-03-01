<?php

declare(strict_types=1);

namespace GC\Combat\Handler;

use GC\App\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerCombatReportExternalHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'player.combat.report.external';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Player/playerCombatReportExternal.twig');
    }
}