<?php

declare(strict_types=1);

namespace GC\User\Model;

use Doctrine\ORM\EntityRepository;

final class UserRepository extends EntityRepository
{
    /**
     * @param mixed $userId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\User\Model\User|null
     */
	public function findById($userId): ?User
    {
		return $this->createQueryBuilder('user')
		    ->where('user.userId = :userId')
            ->setParameter(':userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
	}

    /**
     * @param string $mail
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\User\Model\User|null
     */
	public function findByMail(string $mail): ?User
    {
		return $this->createQueryBuilder('user')
		    ->where('user.mail = :mail')
            ->setParameter(':mail', $mail)
            ->getQuery()
            ->getOneOrNullResult();
	}

    /**
     * @param string $name
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\User\Model\User|null
     */
	public function findByName(string $name): ?User
    {
		return $this->createQueryBuilder('user')
            ->where('user.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()
            ->getOneOrNullResult();
	}
	
	/**
	 * @return \GC\User\Model\User[]
	 */
	public function findAllOrderByName(): array
    {
		return $this->createQueryBuilder('user')
            ->orderBy('user.name')
            ->getQuery()
            ->getResult();
	}

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return int
     */
	public function countUsers(): int
    {
		return (int) $this->createQueryBuilder('user')
            ->select('count(user.userId)')
            ->getQuery()
            ->getSingleScalarResult();
	}
}