<?php

declare(strict_types=1);

namespace GC\User;

use GC\User\Handler\UserLoginHandler;
use GC\User\Handler\UserLogoutHandler;
use GC\User\Handler\UserRegisterHandler;
use Inferno\Routing\Route\RouteCollectionInterface;
use Inferno\Routing\Route\RouteProviderInterface;

final class UserRouteProvider implements RouteProviderInterface
{
    /**
     * @param \Inferno\Routing\Route\RouteCollectionInterface $collection
     *
     * @return void
     */
    public function provide(RouteCollectionInterface $collection): void
    {
        $collection->post('/{locale}/{universe}/user/register', UserRegisterHandler::class);
        $collection->post('/{locale}/{universe}/user/login', UserLoginHandler::class);
        $collection->get('/{locale}/{universe}/user/logout', UserLogoutHandler::class);
    }
}