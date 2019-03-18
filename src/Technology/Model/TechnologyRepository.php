<?php

declare(strict_types=1);

namespace GC\Technology\Model;

use Doctrine\ORM\EntityRepository;

final class TechnologyRepository extends EntityRepository
{
    /**
     * @param int $technologyId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Technology\Model\Technology|null
     */
	public function findById(int $technologyId): ?Technology
    {
        return $this->createQueryBuilder('technology')
		    ->where('technology.technologyId = :technologyId')
            ->setParameter(':technologyId', $technologyId)
            ->getQuery()
            ->getOneOrNullResult();
	}

    /**
     * @param int $universeId
     * @param int $factionId
     *
     * @return \GC\Unit\Model\Unit[]
     */
    public function findPlayerTechnologiesByUniverseAndFaction(int $universeId, int $factionId): array
    {
        return $this->createQueryBuilder('technology')
            ->where('technology.universe = :universeId')
            ->andWhere('technology.faction = :factionId')
            ->andWhere('technology.isPlayerTechnology = 1')
            ->setParameter(':universeId', $universeId)
            ->setParameter(':factionId', $factionId)
            ->getQuery()
            ->getResult();
    }
}