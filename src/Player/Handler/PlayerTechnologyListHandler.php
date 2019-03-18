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

        $buildableTechnologies = $this->filterTechnologies($currentPlayer, $technologies);

        return $this->render('@Player/technology-list.twig', [
            'technologies' => $buildableTechnologies,
        ]);
    }

    /**
     * @param \GC\Player\Model\Player $currentPlayer
     * @param \GC\Technology\Model\Technology[] $technologies
     *
     * @return \GC\Technology\Model\Technology[]
     */
    private function filterTechnologies(Player $currentPlayer, array $technologies): array
    {
        $buildable = [];
        foreach ($technologies as $technology) {
            if ($currentPlayer->hasTechnology($technology) || $currentPlayer->hasTechnologyRequirementsFor($technology)) {
                $buildable[] = $technology;
            }
        }

        return $buildable;
    }

    /**
     * @param \GC\Player\Model\Player $currentPlayer
     *
     * @return \GC\Technology\Model\Technology[]
     */
    private function filterCompletedTechnologies(Player $currentPlayer): array
    {
        $completed = [];
        foreach ($currentPlayer->getPlayerTechnologiesCompleted() as $playerTechnology) {
            $completed[] = $playerTechnology->getTechnology();
        }

        return $completed;
    }
}