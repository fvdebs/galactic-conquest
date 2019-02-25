<?php

declare(strict_types=1);

namespace GC\Faction;

use Doctrine\ORM\EntityManager;
use GC\Faction\Model\FactionRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class FactionServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideFactionRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideFactionRepository(Container $container): void
    {
        $container->offsetSet(FactionRepository::class, function(Container $container) {
            return new FactionRepository($container->offsetGet(EntityManager::class));
        });
    }
}