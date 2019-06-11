<?php

declare(strict_types=1);

namespace GC\Event\Service;

use GC\Event\Model\EventRepository;

final class EventService implements EventServiceInterface
{
    /**
     * @var \GC\Event\Model\EventRepository
     */
    private $eventRepository;

    /**
     * @param \GC\Event\Model\EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }
}
