<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UniverseRankingHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;
    use GameAwareTrait;

    public const NAME = 'universe.ranking';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $universe = $this->getCurrentUniverse($request);

        return $this->render('@Universe/universe-ranking.twig', [
            'allianceRankings' => $this->getAllianceRepository()->findAndSortByRanking($universe->getUniverseId()),
            'galaxyRankings' => $this->getGalaxyRepository()->findAndSortByRanking($universe->getUniverseId()),
            'playerRankings' => $this->getPlayerRepository()->findAndSortByRanking($universe->getUniverseId(), 0, 100),
        ]);
    }
}