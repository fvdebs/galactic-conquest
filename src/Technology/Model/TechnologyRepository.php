<?php

declare(strict_types=1);

namespace GC\Technology\Model;

use Doctrine\ORM\EntityRepository;

final class TechnologyRepository extends EntityRepository
{
    /**
     * @param int $technologyId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Technology\Model\Technology|null
     */
	public function findById(int $technologyId): ?Technology
    {
        return $this->createQueryBuilder('technology')
		    ->where('technology.technology = :technologyId')
            ->setParameter(':technologyId', $technologyId)
            ->getQuery()
            ->getOneOrNullResult();
	}
}