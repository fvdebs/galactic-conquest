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
		parent::__construct($entityManager, Alliance::class);
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
     * @throws \Doctrine\ORM\NonUniqueResultException
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