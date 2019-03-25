<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerFleetHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;
    use GameAwareTrait;

    public const NAME = 'player.fleet';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);

        $movableUnits = $this->getUnitRepository()->findMovableByUniverseAndFaction(
            $currentPlayer->getUniverse()->getUniverseId(),
            $currentPlayer->getFaction()->getFactionId()
        );

        return $this->render('@Player/player-fleet.twig', [
            'movableUnits' => $movableUnits,
        ]);
    }
}
