<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class CombatReportRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, 'GC\Combat\Model\CombatReport');
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('combatReport');
    }

    /**
     * @param int $combatReportId
     *
     * @return \GC\Combat\Model\CombatReport|null
     */
	public function findById(int $combatReportId): ?CombatReport
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('combatReport.combatReportId = :combatReportId')
            ->setParameter(':combatReportId', $combatReportId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}