<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerFleetMissionRecallHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'player.fleet.mission.recall';

    private const FIELD_FLEET_ID = 'identifier';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);

        $validator = $this->getValidatorWith($request->getParsedBody());
        $validator->context(static::FIELD_FLEET_ID)->isRequired()->isInt();

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        $fleetId = (int) $this->getValue(static::FIELD_FLEET_ID, $request);

        $playerFleet = $currentPlayer->getPlayerFleetById($fleetId);

        if ($playerFleet === null || $playerFleet->isRecalling() || !$playerFleet->isBusy()) {
            return $this->redirectJson(PlayerFleetHandler::NAME);
        }

        $playerFleet->recall();

        $this->flush();

        return $this->redirectJson(PlayerFleetHandler::NAME);
    }
}
