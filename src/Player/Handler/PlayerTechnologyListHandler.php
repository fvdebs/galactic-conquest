<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use GC\Player\Model\Player;
use GC\Technology\Model\Technology;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerTechnologyListHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;
    use GameAwareTrait;

    public const NAME = 'player.technology.list';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);

        $technologies = $this->getTechnologyRepository()->findPlayerTechnologiesByUniverseAndFaction(
            $currentPlayer->getUniverse()->getUniverseId(),
            $currentPlayer->getFaction()->getFactionId()
        );

        $buildableTechnologies = $this->sortTechnologiesByName(
            $this->filterBuildableTechnologies($currentPlayer, $technologies)
        );

        $completedTechnologies = $this->sortTechnologiesByName(
            $currentPlayer->getTechnologiesCompleted()
        );

        $inConstructionTechnologies = $this->sortTechnologiesByName(
            $currentPlayer->getTechnologiesInConstruction()
        );

        return $this->render('@Player/player-technology-list.twig', [
            'technologies' => \array_merge($buildableTechnologies, $inConstructionTechnologies, $completedTechnologies),
        ]);
    }

    /**
     * @param \GC\Technology\Model\Technology[] $technologies
     *
     * @return \GC\Technology\Model\Technology[]
     */
    private function sortTechnologiesByName(array $technologies): array
     {
        usort($technologies, function (Technology $technologyFirst, Technology $technologySecond) {
            if ($technologyFirst->getName() === $technologySecond->getName()) {
                return 0;
            }

            if ($technologyFirst->getName() < $technologySecond->getName()) {
                return 1;
            }

            return -1;
        });

        return $technologies;
    }

    /**
     * @param \GC\Player\Model\Player $currentPlayer
     * @param \GC\Technology\Model\Technology[] $technologies
     *
     * @return \GC\Technology\Model\Technology[]
     */
    private function filterBuildableTechnologies(Player $currentPlayer, array $technologies): array
    {
        $buildable = [];

        foreach ($technologies as $technology) {
            if (!$currentPlayer->isPlayerTechnologyCompleted($technology)
                && !$currentPlayer->isPlayerTechnologyInConstruction($technology)
                && $currentPlayer->hasTechnologyRequirementsFor($technology)
            ) {
                $buildable[] = $technology;
            }
        }

        return $buildable;
    }
}