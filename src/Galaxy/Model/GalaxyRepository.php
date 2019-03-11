<?php

declare(strict_types=1);

namespace GC\Galaxy\Model;

use Doctrine\ORM\EntityRepository;

final class GalaxyRepository extends EntityRepository
{
    /**
     * @param int $galaxyId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Galaxy\Model\Galaxy|null
     */
	public function findById(int $galaxyId): ?Galaxy
    {
        return $this->createQueryBuilder('galaxy')
		    ->where('galaxy.galaxyId = :galaxyId')
            ->setParameter(':galaxyId', $galaxyId)
            ->getQuery()
            ->getOneOrNullResult();
	}

    /**
     * @param string $password
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Galaxy\Model\Galaxy|null
     */
    public function findByPassword(string $password): ?Galaxy
    {
        return $this->createQueryBuilder('galaxy')
            ->where('galaxy.password = :password')
            ->setParameter(':password', $password)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return \GC\Galaxy\Model\Galaxy|null
     */
    public function findPreviousGalaxy(Galaxy $galaxy): ?Galaxy
    {
        return $this->createQueryBuilder('galaxy')
            ->where('galaxy.universeId = :universeId')
            ->andWhere('galaxy.number < :number')
            ->setParameter(':universeId', $galaxy->getUniverse()->getUniverseId())
            ->setParameter(':number', $galaxy->getNumber())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return \GC\Galaxy\Model\Galaxy|null
     */
    public function findNextGalaxy(Galaxy $galaxy): ?Galaxy
    {
        return $this->createQueryBuilder('galaxy')
            ->where('galaxy.universeId = :universeId')
            ->andWhere('galaxy.number > :number')
            ->setParameter(':universeId', $galaxy->getUniverse()->getUniverseId())
            ->setParameter(':number', $galaxy->getNumber())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}