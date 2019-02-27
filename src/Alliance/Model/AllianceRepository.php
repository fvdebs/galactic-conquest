<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use Doctrine\ORM\EntityRepository;

final class AllianceRepository extends EntityRepository
{
    /**
     * @param int $allianceId
     *
     * @return \GC\Alliance\Model\Alliance|null
     */
	public function findById(int $allianceId): ?Alliance
    {
		$queryBuilder = $this->createQueryBuilder('alliance');
		$queryBuilder->where('alliance.allianceId = :allianceId')
            ->setParameter(':allianceId', $allianceId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}