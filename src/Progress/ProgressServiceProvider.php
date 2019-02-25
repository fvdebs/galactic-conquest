<?php

declare(strict_types=1);

namespace GC\Progress;

use Doctrine\ORM\EntityManager;
use GC\Progress\Model\ProgressRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class ProgressServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideProgressRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideProgressRepository(Container $container): void
    {
        $container->offsetSet(ProgressRepository::class, function(Container $container) {
            return new ProgressRepository($container->offsetGet(EntityManager::class));
        });
    }
}