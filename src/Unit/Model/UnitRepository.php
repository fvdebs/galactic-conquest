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
     * @return \GC\Unit\Model\Unit[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('unit')
            ->getQuery()
            ->getResult();
    }
}