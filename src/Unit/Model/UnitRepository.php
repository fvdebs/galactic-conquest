<?php

declare(strict_types=1);

namespace GC\Unit\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class UnitRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, Unit::class);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('unit');
    }

    /**
     * @param int $unitId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\User\Model\User|null
     */
	public function findById(int $unitId): ?Unit
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('unit.unitId = :unitId')
            ->setParameter(':unitId', $unitId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}