<?php

declare(strict_types=1);

namespace GC\User;

use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;
use Psr\Container\ContainerInterface;

final class UserRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        // register user
        $collection->post('/{locale}/user/register', function(ContainerInterface $container) {

        }, 'user.register.post');

        // login user
        $collection->post('/{locale}/user/login', function(ContainerInterface $container) {

        }, 'user.login.post');

        // login logout
        $collection->post('/{locale}/user/logout', function(ContainerInterface $container) {

        }, 'user.logout.post');
    }
}