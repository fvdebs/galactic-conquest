<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerUnitListHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'player.unit.list';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);
        $units = $this->getUnitRepository()->findByUniverseAndFaction(
            $currentPlayer->getUniverse()->getUniverseId(),
            $currentPlayer->getFaction()->getFactionId()
        );

        return $this->render('@Player/playerUnitList.twig', [
            'units' => $units,
        ]);
    }
}