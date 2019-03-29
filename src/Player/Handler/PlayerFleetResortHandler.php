<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerFleetResortHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'player.fleet.resort';

    private const FIELD_QUANTITY = 'quantity';
    private const FIELD_PLAYER_FLEET_FROM = 'from';
    private const FIELD_PLAYER_FLEET_TO = 'to';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);

        $quantityArray = $this->getValue(static::FIELD_QUANTITY, $request);
        $playerFleetFromArray = $this->getValue(static::FIELD_PLAYER_FLEET_FROM, $request);
        $playerFleetToArray = $this->getValue(static::FIELD_PLAYER_FLEET_TO, $request);

        $currentPlayer->moveUnits($quantityArray, $playerFleetFromArray, $playerFleetToArray);

        $this->flush();

        return $this->redirectJson(PlayerFleetHandler::NAME);
    }
}
