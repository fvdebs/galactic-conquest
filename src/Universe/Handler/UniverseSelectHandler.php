<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use GC\User\Model\User;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UniverseSelectHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;
    use GameAwareTrait;

    public const NAME = 'universe.select';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->getCurrentUser($request);
        $universes = $this->getUniverseRepository()->findAll();

        return $this->render('@Universe/universe-select.twig', [
            'playableUniverses' => $this->filterPlayableUniverses($user, $universes),
            'joinableUniverses' => $this->filterJoinableUniverses($user, $universes),
            'closedUniverses' => $this->getUniverseRepository()->findAllClosed(),
        ]);
    }

    /**
     * @param \GC\User\Model\User $user
     * @param \GC\Universe\Model\Universe[] $universes
     *
     * @return \GC\Universe\Model\Universe[]
     */
    private function filterPlayableUniverses(User $user, array $universes): array
    {
        $playableUniverses = [];
        foreach ($universes as $universe) {
            if (!$universe->isClosed() && $user->hasPlayerIn($universe)) {
                $playableUniverses[] = $universe;
            }
        }

        return $playableUniverses;
    }

    /**
     * @param \GC\User\Model\User $user
     * @param \GC\Universe\Model\Universe[] $universes
     *
     * @return \GC\Universe\Model\Universe[]
     */
    private function filterJoinableUniverses(User $user, array $universes): array
    {
        $joinableUniverses = [];
        foreach ($universes as $universe) {
            if (!$universe->isClosed() && !$user->hasPlayerIn($universe)) {
                $joinableUniverses[] = $universe;
            }
        }

        return $joinableUniverses;
    }
}