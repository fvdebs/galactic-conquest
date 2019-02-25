<?php

declare(strict_types=1);

namespace GC\Galaxy\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class GalaxyRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, Galaxy::class);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('galaxy');
    }

    /**
     * @param int $galaxyId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Galaxy\Model\Galaxy|null
     */
	public function findById(int $galaxyId): ?Galaxy
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('galaxy.galaxyId = :galaxyId')
            ->setParameter(':galaxyId', $galaxyId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}