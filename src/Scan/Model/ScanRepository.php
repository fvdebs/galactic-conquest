<?php

declare(strict_types=1);

namespace GC\User\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Inferno\Doctrine\Repository\DoctrineRepository;

class UserRepository extends DoctrineRepository
{
	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
    {
		parent::__construct($entityManager, 'GC\User\Model\User');
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
	protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('user');
    }

    /**
     * @param int $userId
     *
     * @return \GC\User\Model\User|null
     */
	public function findById(int $userId): ?User
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('user.userId = :userId')
            ->setParameter(':userId', $userId);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}

    /**
     * @param string $mail
     *
     * @return \GC\User\Model\User|null
     */
	public function findByMail(string $mail): ?User
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('user.mail = :mail')
            ->setParameter(':mail', $mail);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}

    /**
     * @param string $name
     *
     * @return \GC\User\Model\User|null
     */
	public function findByName(string $name): ?User
    {
		$queryBuilder = $this->getQueryBuilder();
		$queryBuilder->where('user.name = :name')
            ->setParameter(':name', $name);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
	
	/**
	 * @return User[]
	 */
	public function findAllOrderByName(): array
    {
		$queryBuilder = $this->getRepository()->createQueryBuilder('user');
		$queryBuilder->orderBy('user.name');

		return $queryBuilder->getQuery()->getResult();
	}
	
	/**
	 * @return int
	 */
	public function countUsers(): int
    {
		$count = $this->getQueryBuilder()
            ->select('count(user.userId)')
            ->getQuery()
            ->getSingleScalarResult();

		return (int) $count;
	}
}