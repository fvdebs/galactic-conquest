<?php

declare(strict_types=1);

namespace GC\Player\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class PlayerRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, Player::class);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('player');
    }

    /**
     * @param int $playerId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Player\Model\Player|null
     */
	public function findById(int $playerId): ?Player
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('player.playerId = :playerId')
            ->setParameter(':playerId', $playerId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
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
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->where('player.user = :userId')
            ->andWhere('player.universe = :universeId')
            ->setParameter(':userId', $userId)
            ->setParameter(':universeId', $universeId);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}