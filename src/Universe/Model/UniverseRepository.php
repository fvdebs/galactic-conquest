<?php

declare(strict_types=1);

namespace GC\Universe\Model;

use DateTime;
use Doctrine\ORM\EntityRepository;

final class UniverseRepository extends EntityRepository
{
    /**
     * @param int $universeId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Universe\Model\Universe|null
     */
	public function findById(int $universeId): ?Universe
    {
		return $this->createQueryBuilder('universe')
		    ->where('universe.universeId = :universeId')
            ->setParameter(':universeId', $universeId)
            ->getQuery()
            ->getOneOrNullResult();
	}

    /**
     * @param string|null $name
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Universe\Model\Universe|null
     */
    public function findByName(?string $name): ?Universe
    {
        if ($name === null) {
            return null;
        }

        return $this->createQueryBuilder('universe')
            ->where('universe.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return \GC\Universe\Model\Universe[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('universe')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return \GC\Universe\Model\Universe[]
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('universe')
            ->where('universe.isActive = 1')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return \GC\Universe\Model\Universe[]
     */
    public function findAllClosed(): array
    {
        return $this->createQueryBuilder('universe')
            ->where('universe.isClosed = 1')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Exception
     *
     * @return \GC\Universe\Model\Universe[]
     */
    public function findStartedAndActiveUniverses(): array
    {
        return $this->createQueryBuilder('universe')
            ->where('universe.ticksStartingAt IS NOT NULL')
            ->andWhere('universe.ticksStartingAt < :currentDate')
            ->andWhere('universe.isActive = 1')
            ->andWhere('universe.isClosed = 0')
            ->setParameter(':currentDate', new DateTime())
            ->getQuery()
            ->getResult();
    }
}
