<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    /**
     * @param int $universeId
     * @param int $start
     * @param int $limit
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator|\GC\Alliance\Model\Alliance[]
     */
    public function findAndSortByRanking(int $universeId, int $start = 0, int $limit = 50): Paginator
    {
        $query = $this->createQueryBuilder('alliance')
            ->where('alliance.universe = :universeId')
            ->andWhere('alliance.rankingPosition IS NOT NULL')
            ->andWhere('alliance.rankingPosition != 0')
            ->orderBy('alliance.rankingPosition', 'ASC')
            ->setParameter(':universeId', $universeId)
            ->setFirstResult($start)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }
}