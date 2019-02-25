<?php

declare(strict_types=1);

namespace GC\Progress\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

class ProgressRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, Progress::class);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('progress');
    }

    /**
     * @param int $userId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Progress\Model\Progress|null
     */
	public function findById(int $userId): ?Progress
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('progress.progressId = :progressId')
            ->setParameter(':progressId', $userId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}