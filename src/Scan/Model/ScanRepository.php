<?php

declare(strict_types=1);

namespace GC\Scan\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class ScanRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, Scan::class);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('scan');
    }

    /**
     * @param int $scanId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Scan\Model\Scan|null
     */
	public function findById(int $scanId): ?Scan
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('scan.scanId = :scanId')
            ->setParameter(':scanId', $scanId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}