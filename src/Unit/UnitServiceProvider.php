<?php

declare(strict_types=1);

namespace GC\Unit;

use Doctrine\ORM\EntityManager;
use GC\Unit\Model\UnitRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class UnitServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideUnitRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUnitRepository(Container $container): void
    {
        $container->offsetSet(UnitRepository::class, function(Container $container) {
            return new UnitRepository($container->offsetGet(EntityManager::class));
        });
    }
}