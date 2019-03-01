<?php

declare(strict_types=1);

namespace GC\Scan\Model;

use Doctrine\ORM\EntityRepository;

final class ScanRepository extends EntityRepository
{
    /**
     * @param int $scanId
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \GC\Scan\Model\Scan|null
     */
	public function findById(int $scanId): ?Scan
    {
        return $this->createQueryBuilder('scan')
		    ->where('scan.scanId = :scanId')
            ->setParameter(':scanId', $scanId)
            ->getQuery()
            ->getOneOrNullResult();
	}
}