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
}