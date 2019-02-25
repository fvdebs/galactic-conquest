<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class AllianceRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, 'GC\Alliance\Model\Alliance');
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('alliance');
    }

    /**
     * @param int $allianceId
     *
     * @return \GC\Alliance\Model\Alliance|null
     */
	public function findById(int $allianceId): ?Alliance
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('alliance.allianceId = :allianceId')
            ->setParameter(':allianceId', $allianceId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}