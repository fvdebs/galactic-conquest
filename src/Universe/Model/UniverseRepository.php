<?php

declare(strict_types=1);

namespace GC\Universe\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

final class UniverseRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, Universe::class);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('universe');
    }

    /**
     * @param int $universeId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Universe\Model\Universe|null
     */
	public function findById(int $universeId): ?Universe
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('universe.universeId = :universeId')
            ->setParameter(':universeId', $universeId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}

    /**
     * @param string $name
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Universe\Model\Universe|null
     */
    public function findByName(string $name): ?Universe
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->where('universe.name = :name')
            ->setParameter(':name', $name);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}