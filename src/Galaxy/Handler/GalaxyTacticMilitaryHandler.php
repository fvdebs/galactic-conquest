<?php

declare(strict_types=1);

namespace GC\Galaxy\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use GC\Technology\Model\Technology;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GalaxyTacticMilitaryHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'galaxy.tactic.military';
    private const ATTRIBUTE_GALAXY_POSITION = 'galaxyPosition';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);

        $player = $this->getPlayerRepository()->findByPositionAndUniverseId(
            $currentPlayer->getGalaxy()->getGalaxyId(),
            (int) $request->getAttribute(static::ATTRIBUTE_GALAXY_POSITION),
            $currentPlayer->getUniverse()->getUniverseId()
        );

        if ($player === null
//            || !$player->getGalaxy()->hasTechnologyByFeatureKey(Technology::FEATURE_GALAXY_TACTIC_FLEET)
            || $player->getGalaxy()->getGalaxyId() !== $currentPlayer->getGalaxy()->getGalaxyId()
            || $player->getUniverse()->getUniverseId() !== $currentPlayer->getUniverse()->getUniverseId()
        ) {
            return $this->redirect(GalaxyTacticHandler::NAME);
        }

        $movableUnits = $this->getUnitRepository()->findMovableByUniverseAndFaction(
            $currentPlayer->getUniverse()->getUniverseId(),
            $player->getFaction()->getFactionId()
        );

        $stationaryUnits = $this->getUnitRepository()->findStationaryByUniverseAndFaction(
            $currentPlayer->getUniverse()->getUniverseId(),
            $player->getFaction()->getFactionId()
        );

        return $this->render('@Galaxy/galaxy-tactic-military.twig', [
            'player' => $player,
            'movableUnits' => $movableUnits,
            'stationaryUnits' => $stationaryUnits,
        ]);
    }
}
