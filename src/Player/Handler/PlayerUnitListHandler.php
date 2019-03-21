<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use GC\Player\Model\Player;
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

        $buildableUnits = $this->filterBuildableUnits($currentPlayer, $units);
        $flyableBuildableUnits = $this->filterFlyableUnits($buildableUnits);
        $stationaryBuildableUnits = $this->filterStationaryUnits($buildableUnits);

        return $this->render('@Player/player-unit-list.twig', [
            'flyableBuildableUnits' => $flyableBuildableUnits,
            'stationaryBuildableUnits' => $stationaryBuildableUnits,
        ]);
    }

    /**
     * @param \GC\Player\Model\Player $currentPlayer
     * @param \GC\Unit\Model\Unit[] $units
     *
     * @return \GC\Unit\Model\Unit[]
     */
    private function filterBuildableUnits(Player $currentPlayer, array $units): array
    {
        $buildableUnits = [];
        foreach ($units as $unit) {
            if ($currentPlayer->hasUnitRequirementsFor($unit)) {
                $buildableUnits[] = $unit;
            }
        }

        return $buildableUnits;
    }

    /**
     * @param \GC\Unit\Model\Unit[] $units
     *
     * @return \GC\Unit\Model\Unit[]
     */
    private function filterFlyableUnits(array $units): array
    {
        $flyableBuildableUnits = [];
        foreach ($units as $unit) {
            if (!$unit->isStationary()) {
                $flyableBuildableUnits[] = $unit;
            }
        }

        return $flyableBuildableUnits;
    }

    /**
     * @param \GC\Unit\Model\Unit[] $units
     *
     * @return \GC\Unit\Model\Unit[]
     */
    private function filterStationaryUnits(array $units): array
    {
        $stationaryBuildableUnits = [];
        foreach ($units as $unit) {
            if ($unit->isStationary()) {
                $stationaryBuildableUnits[] = $unit;
            }
        }

        return $stationaryBuildableUnits;
    }
}
