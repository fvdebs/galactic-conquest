<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use Doctrine\ORM\EntityRepository;

final class CombatReportRepository extends EntityRepository
{
    /**
     * @param int $combatReportId
     *
     * @return \GC\Combat\Model\CombatReport|null
     */
	public function findById(int $combatReportId): ?CombatReport
    {
		$queryBuilder = $this->createQueryBuilder('combatReport');
		$queryBuilder->where('combatReport.combatReportId = :combatReportId')
            ->setParameter(':combatReportId', $combatReportId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}