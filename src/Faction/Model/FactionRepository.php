<?php

declare(strict_types=1);

namespace GC\Faction\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class FactionRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, Faction::class);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('faction');
    }

    /**
     * @param int $factionId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\User\Model\User|null
     */
	public function findById(int $factionId): ?Faction
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('faction.factionId = :factionId')
            ->setParameter(':factionId', $factionId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}