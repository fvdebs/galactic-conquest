<?php

declare(strict_types=1);

namespace GC\Technology\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class TechnologyRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, Technology::class);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('technology');
    }

    /**
     * @param int $technologyId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Technology\Model\Technology|null
     */
	public function findById(int $technologyId): ?Technology
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('technology.technology = :technologyId')
            ->setParameter(':technologyId', $technologyId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}