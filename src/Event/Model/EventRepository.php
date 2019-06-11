<?php

declare(strict_types=1);

namespace GC\Event\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class EventRepository extends EntityRepository
{
    /**
     * @param int $eventId
     *
     * @return \GC\Event\Model\Event|null
     */
    public function findById(int $eventId): ?Event
    {
        return $this->createQueryBuilder('event')
            ->where('event.eventId = :eventId')
            ->setParameter(':eventId', $eventId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $playerId
     * @param int $start - default: 0
     * @param int $limit - default: 100
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function findByPlayerIdOrderByCreatedAt(int $playerId, int $start = 0, int $limit = 100): Paginator
    {
        return new Paginator(
            $this->createQueryBuilder('event')
                ->where('event.player = :playerId')
                ->setParameter(':playerId', $playerId)
                ->orderBy('event.createdAt', 'ASC')
                ->setFirstResult($start)
                ->setMaxResults($limit)
                ->getQuery()
        );
    }

    /**
     * @return \GC\Event\Model\Event[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('event')
            ->getQuery()
            ->getResult();
    }
}
