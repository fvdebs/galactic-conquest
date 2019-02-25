<?php

declare(strict_types=1);

namespace GC\User;

use Doctrine\ORM\EntityManager;
use GC\User\Model\UserRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class UserServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideUserRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    protected function provideUserRepository(Container $container): void
    {
        $container->offsetSet(UserRepository::class, function(Container $container) {
            return new UserRepository(
                $container->offsetGet(EntityManager::class)
            );
        });
    }
}
