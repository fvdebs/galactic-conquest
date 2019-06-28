<?php

declare(strict_types=1);

namespace GC\Player\Model;

use Doctrine\ORM\EntityRepository;

final class PlayerCombatReportRepository extends EntityRepository
{
    /**
     * @param int $combatReportId
     *
     * @return \GC\Player\Model\PlayerCombatReport|null
     */
    public function findById(int $combatReportId): ?PlayerCombatReport
    {
        return $this->createQueryBuilder('playerCombatReport')
            ->where('playerCombatReport.combatReportId = :combatReportId')
            ->setParameter(':combatReportId', $combatReportId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $externalId
     *
     * @return \GC\Player\Model\PlayerCombatReport|null
     */
    public function findByExternalId(string $externalId): ?PlayerCombatReport
    {
        return $this->createQueryBuilder('playerCombatReport')
            ->where('playerCombatReport.externalId = :externalId')
            ->setParameter(':externalId', $externalId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
