<?php

declare(strict_types=1);

namespace GC\Unit\Model;

use Doctrine\ORM\EntityRepository;

final class UnitRepository extends EntityRepository
{
    /**
     * @param int $unitId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Unit\Model\Unit|null
     */
	public function findById(int $unitId): ?Unit
    {
        return $this->createQueryBuilder('unit')
		    ->where('unit.unitId = :unitId')
            ->setParameter(':unitId', $unitId)
            ->getQuery()
            ->getOneOrNullResult();
	}

    /**
     * @param int $universeId
     * @param int $factionId
     *
     * @return \GC\Unit\Model\Unit[]
     */
    public function findByUniverseAndFaction(int $universeId, int $factionId): array
    {
        return $this->createQueryBuilder('unit')
            ->where('unit.universe = :universeId')
            ->andWhere('unit.faction = :factionId')
            ->setParameter(':universeId', $universeId)
            ->setParameter(':factionId', $factionId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $universeId
     * @param int $factionId
     *
     * @return \GC\Unit\Model\Unit[]
     */
    public function findMovableByUniverseAndFaction(int $universeId, int $factionId): array
    {
        return $this->createQueryBuilder('unit')
            ->where('unit.universe = :universeId')
            ->andWhere('unit.faction = :factionId')
            ->andWhere('unit.isStationary = 0')
            ->setParameter(':universeId', $universeId)
            ->setParameter(':factionId', $factionId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $universeId
     * @param int $factionId
     *
     * @return \GC\Unit\Model\Unit[]
     */
    public function findStationaryByUniverseAndFaction(int $universeId, int $factionId): array
    {
        return $this->createQueryBuilder('unit')
            ->where('unit.universe = :universeId')
            ->andWhere('unit.faction = :factionId')
            ->andWhere('unit.isStationary = 1')
            ->setParameter(':universeId', $universeId)
            ->setParameter(':factionId', $factionId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return \GC\Unit\Model\Unit[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('unit')
            ->getQuery()
            ->getResult();
    }
}