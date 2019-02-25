<?php

declare(strict_types=1);

namespace GC\User\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

class FactionRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, 'GC\Faction\Model\Faction');
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
     * @return \GC\Faction\Model\Faction|null
     */
	public function findById(int $factionId): ?User
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('faction.factionId = :factionId')
            ->setParameter(':factionId', $factionId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
}