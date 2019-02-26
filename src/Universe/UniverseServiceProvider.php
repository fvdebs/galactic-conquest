<?php

declare(strict_types=1);

namespace GC\Universe;

use Doctrine\ORM\EntityManager;
use GC\Universe\Model\UniverseRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class UniverseServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideUniverseRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUniverseRepository(Container $container): void
    {
        $container->offsetSet(UniverseRepository::class, function(Container $container) {
            return new UniverseRepository($container->offsetGet(EntityManager::class));
        });
    }
}