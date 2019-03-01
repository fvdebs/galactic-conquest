<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use Doctrine\ORM\EntityRepository;

final class AllianceRepository extends EntityRepository
{
    /**
     * @param int $allianceId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Alliance\Model\Alliance|null
     */
	public function findById(int $allianceId): ?Alliance
    {
		return $this->createQueryBuilder('alliance')
		    ->where('alliance.allianceId = :allianceId')
            ->setParameter(':allianceId', $allianceId)
            ->getQuery()
            ->getOneOrNullResult();
	}
}