<?php

declare(strict_types=1);

namespace GC\Technology;

use Doctrine\ORM\EntityManager;
use GC\Technology\Model\TechnologyRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class TechnologyServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideTechnologyRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideTechnologyRepository(Container $container): void
    {
        $container->offsetSet(TechnologyRepository::class, function(Container $container) {
            return new TechnologyRepository($container->offsetGet(EntityManager::class));
        });
    }
}