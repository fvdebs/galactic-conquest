<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function json_decode;

final class PlayerCombatReportExternalHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'player.combat.report.external';

    private const REQUEST_EXTERNAL_ID = 'externalId';
    private const REPORT_KEY_PLAYER_ID = 'playerId';
    private const REPORT_KEY_DATA = 'data';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $externalId = (string) $request->getAttribute(static::REQUEST_EXTERNAL_ID);

        $combatReport = $this->getPlayerCombatReportRepository()
            ->findByExternalId($externalId);

        if ($combatReport === null) {
            return $this->render('@Player/playerCombatReportExternalNotFound.twig', [
                'externalId' => $externalId
            ]);
        }

        $data = json_decode($combatReport->getDataJson());
        $playerId = $combatReport->getPlayer()->getPlayerId();
        $isDefense = true;

        return $this->render('@Player/playerCombatReportExternal.twig', [

        ]);
    }
}