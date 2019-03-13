<?php

declare(strict_types=1);

namespace GC\User;

use Doctrine\ORM\EntityManager;
use GC\User\Handler\UserLoginHandler;
use GC\User\Handler\UserLogoutHandler;
use GC\User\Handler\UserRegisterHandler;
use GC\User\Model\User;
use GC\User\Model\UserRepository;
use Inferno\Routing\Route\RouteCollection;
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
        $this->provideUserRoutes($pimple);
        $this->provideUserRepository($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUserRoutes(Container $container): void
    {
        $container->extend(RouteCollection::class, function (RouteCollection $collection, Container $container)
        {
            $collection->post('/{locale}/register', UserRegisterHandler::class)->addAttribute('public', true);
            $collection->post('/{locale}/login', UserLoginHandler::class)->addAttribute('public', true);
            $collection->get('/{locale}/logout', UserLogoutHandler::class);

            return $collection;
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUserRepository(Container $container): void
    {
        $container->offsetSet(UserRepository::class, function (Container $container) {
            return $container->offsetGet(EntityManager::class)->getRepository(User::class);
        });
    }
}