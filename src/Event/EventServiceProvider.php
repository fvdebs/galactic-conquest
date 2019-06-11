<?php

declare(strict_types=1);

namespace GC\Event;

use Doctrine\ORM\EntityManager;
use GC\Event\Model\Event;
use GC\Event\Model\EventRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideEventLogRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideEventLogRepository(Container $container): void
    {
        $container->offsetSet(EventRepository::class, function (Container $container) {
            return $container->offsetGet(EntityManager::class)->getRepository(Event::class);
        });
    }
}