<?php

declare(strict_types=1);

namespace GC\Galaxy\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use GC\Galaxy\Model\Galaxy;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GalaxyGalaxiesHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'galaxy.galaxies';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $galaxy = $this->getGalaxyRepository()->findByNumber(
            (int) $request->getAttribute('number'),
            $this->getCurrentUniverse($request)->getUniverseId()
        );

        if ($galaxy === null) {
            return $this->redirect(static::NAME, [
                'number' => $this->getCurrentPlayer($request)->getGalaxy()->getNumber()
            ]);
        }

        return $this->render('@Galaxy/galaxy-galaxies.twig', [
            'galaxy' => $galaxy,
            'previousGalaxyNumber' => $this->getPreviousGalaxyNumberOf($galaxy),
            'nextGalaxyNumber' => $this->getNextGalaxyNumberOf($galaxy),
        ]);
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return int|null
     */
    private function getPreviousGalaxyNumberOf(Galaxy $galaxy): ?int
    {
        $previousGalaxy = $this->getGalaxyRepository()->findPreviousGalaxy($galaxy);
        if ($previousGalaxy === null) {
            $previousGalaxy = $this->getGalaxyRepository()->findLowestGalaxyNumber($galaxy->getUniverse()->getUniverseId());
        }

        if ($previousGalaxy === null) {
            return null;
        }

        return $previousGalaxy->getNumber();
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return int|null
     */
    private function getNextGalaxyNumberOf(Galaxy $galaxy): ?int
    {
        $nextGalaxy = $this->getGalaxyRepository()->findNextGalaxy($galaxy);
        if ($nextGalaxy === null) {
            $nextGalaxy = $this->getGalaxyRepository()->findHighestGalaxyNumber($galaxy->getUniverse()->getUniverseId());
        }

        if ($nextGalaxy === null) {
            return null;
        }

        return $nextGalaxy->getNumber();
    }
}