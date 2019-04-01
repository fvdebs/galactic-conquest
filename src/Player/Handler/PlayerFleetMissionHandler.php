<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerFleetMissionHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'player.fleet.mission';

    private const FIELD_FLEET_ID = 'fleetId';
    private const FIELD_GALAXY_NUMBER = 'galaxyNumber';
    private const FIELD_GALAXY_POSITION = 'galaxyPosition';
    private const FIELD_MISSION_TICKS = 'missionTicks';
    private const FIELD_MISSION = 'mission';
    private const FIELD_MISSION_VALUES = ['offensive', 'defensive'];
    private const FIELD_MISSION_VALUE_OFFENSIVE = 'offensive';
    private const FIELD_MISSION_VALUE_DEFENSIVE = 'defensive';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);
        $currentUniverse = $currentPlayer->getUniverse();
        $currentUniverseId = $currentUniverse->getUniverseId();

        $validator = $this->getValidatorWith($request->getParsedBody());
        $validator->context(static::FIELD_FLEET_ID)->isRequired()->isInt();
        $validator->context(static::FIELD_GALAXY_NUMBER)->isRequired()->isInt();
        $validator->context(static::FIELD_GALAXY_POSITION)->isRequired()->isInt();
        $validator->context(static::FIELD_MISSION)->isRequired()->isIn(static::FIELD_MISSION_VALUES);
        $validator->context(static::FIELD_MISSION_TICKS)->isRequired()->isInt();

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        $fleetId = (int) $this->getValue(static::FIELD_FLEET_ID, $request);
        $galaxyNumber = (int) $this->getValue(static::FIELD_GALAXY_NUMBER, $request);
        $galaxyPosition = (int) $this->getValue(static::FIELD_GALAXY_POSITION, $request);
        $mission = $this->getValue(static::FIELD_MISSION, $request);
        $missionTicks = (int) $this->getValue(static::FIELD_MISSION_TICKS, $request);

        $galaxy = $this->getGalaxyRepository()->findByNumber($galaxyNumber, $currentUniverseId);

        if ($galaxy === null) {
            $validator->addMessage(static::FIELD_GALAXY_NUMBER);
            return $this->failedValidation($validator);
        }

        $targetPlayer = $this->getPlayerRepository()->findByPositionAndUniverseId($galaxy->getGalaxyId(), $galaxyPosition, $currentUniverseId);

        if ($targetPlayer === null) {
            $validator->addMessage(static::FIELD_GALAXY_POSITION);
            return $this->failedValidation($validator);
        }

        if ($mission !== static::FIELD_MISSION_VALUE_DEFENSIVE && $currentPlayer->isInSameAllianceAs($targetPlayer)) {
            $validator->addMessage(static::FIELD_MISSION, 'player.fleet.same.alliance');
            return $this->failedValidation($validator);
        }

        if ($mission !== static::FIELD_MISSION_VALUE_DEFENSIVE && $currentPlayer->isInSameGalaxyAs($targetPlayer)) {
            $validator->addMessage(static::FIELD_MISSION, 'player.fleet.same.galaxy');
            return $this->failedValidation($validator);
        }

        $playerFleet = $currentPlayer->getPlayerFleetById($fleetId);

        if ($playerFleet === null) {
            return $this->redirectJson(PlayerFleetHandler::NAME);
        }

        if ($playerFleet->getUnitQuantity() === 0) {
            $validator->addMessage(static::FIELD_MISSION, 'player.fleet.empty');
            return $this->failedValidation($validator);
        }

        if (!$playerFleet->hasEnoughCarrierSpaceToStart()) {
            $validator->addMessage(static::FIELD_MISSION, 'player.fleet.not.enough.carrier.space');
            return $this->failedValidation($validator);
        }

        if ($currentPlayer->getPlayerId() === $targetPlayer->getPlayerId()) {
            $validator->addMessage(static::FIELD_GALAXY_NUMBER);
            return $this->failedValidation($validator);
        }

        if ($mission === static::FIELD_MISSION_VALUE_DEFENSIVE && ($missionTicks < 1 || $missionTicks > $currentUniverse->getMaxTicksMissionDefensive())) {
            $validator->addMessage(static::FIELD_MISSION_TICKS);
            return $this->failedValidation($validator);
        }

        if ($mission === static::FIELD_MISSION_VALUE_DEFENSIVE && ($missionTicks < 1 || $missionTicks > $currentUniverse->getMaxTicksMissionDefensive())) {
            $validator->addMessage(static::FIELD_MISSION_TICKS);
            return $this->failedValidation($validator);
        }

        if ($mission === static::FIELD_MISSION_VALUE_OFFENSIVE && ($missionTicks < 1 || $missionTicks > $currentUniverse->getMaxTicksMissionOffensive())) {
            $validator->addMessage(static::FIELD_MISSION_TICKS);
            return $this->failedValidation($validator);
        }

        if ($mission === static::FIELD_MISSION_VALUE_DEFENSIVE && $currentPlayer->isAttackingAndIsTarget($targetPlayer)) {
            $validator->addMessage(static::FIELD_MISSION, 'player.fleet.can.not.attack.cause.defending');
            return $this->failedValidation($validator);
        }

        if ($mission === static::FIELD_MISSION_VALUE_OFFENSIVE) {
            $playerFleet->attack($targetPlayer, $missionTicks);
        } else if ($mission === static::FIELD_MISSION_VALUE_DEFENSIVE) {
            $playerFleet->defend($targetPlayer, $missionTicks);
        }

        $this->flush();

        return $this->redirectJson(PlayerFleetHandler::NAME);
    }
}
