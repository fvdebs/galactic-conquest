<?php

declare(strict_types=1);

namespace GC\Faction\Model;

use Doctrine\ORM\EntityRepository;

final class FactionRepository extends EntityRepository
{
    /**
     * @param int $factionId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Faction\Model\Faction|null
     */
    public function findById(int $factionId): ?Faction
    {
        return $this->createQueryBuilder('faction')
            ->where('faction.factionId = :factionId')
            ->setParameter(':factionId', $factionId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}