<?php

declare(strict_types=1);

namespace GC\Player\Model;

use Doctrine\ORM\EntityRepository;

final class PlayerRepository extends EntityRepository
{
    /**
     * @param int $playerId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
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
     * @throws \Doctrine\ORM\NonUniqueResultException
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
}