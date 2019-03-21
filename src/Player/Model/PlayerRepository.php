<?php

declare(strict_types=1);

namespace GC\Player\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class PlayerRepository extends EntityRepository
{
    /**
     * @param int $playerId
     *
     * @return \GC\Player\Model\Player|null
     */
	public function findById(int $playerId): ?Player
    {
		return $this->createQueryBuilder('player')
		    ->where('player.playerId = :playerId')
            ->setParameter(':playerId', $playerId)
            ->getQuery()
            ->getOneOrNullResult();
	}

    /**
     * @param int $userId
     * @param int $universeId
     *
     * @return \GC\Player\Model\Player|null
     */
    public function findByUserIdAndUniverseId(int $userId, int $universeId): ?Player
    {
        return $this->createQueryBuilder('player')
            ->where('player.user = :userId')
            ->andWhere('player.universe = :universeId')
            ->setParameter(':userId', $userId)
            ->setParameter(':universeId', $universeId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $galaxyId
     * @param int $galaxyPosition
     * @param int $universeId
     *
     * @return \GC\Player\Model\Player|null
     */
    public function findByPositionAndUniverseId(int $galaxyId, int $galaxyPosition, int $universeId): ?Player
    {
        return $this->createQueryBuilder('player')
            ->where('player.galaxy = :galaxyId')
            ->andWhere('player.galaxyPosition = :galaxyPosition')
            ->andWhere('player.universe = :universeId')
            ->setParameter(':galaxyId', $galaxyId)
            ->setParameter(':galaxyPosition', $galaxyPosition)
            ->setParameter(':universeId', $universeId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $universeId
     * @param int $start
     * @param int $limit
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator|\GC\Player\Model\Player[]
     */
    public function findAndSortByRanking(int $universeId, int $start = 0, int $limit = 50): Paginator
    {
        $query = $this->createQueryBuilder('player')
            ->where('player.universe = :universeId')
            ->andWhere('player.rankingPosition IS NOT NULL')
            ->andWhere('player.rankingPosition != 0')
            ->orderBy('player.rankingPosition', 'ASC')
            ->setParameter(':universeId', $universeId)
            ->setFirstResult($start)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }
}
