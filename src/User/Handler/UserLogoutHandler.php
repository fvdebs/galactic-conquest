<?php

declare(strict_types=1);

namespace GC\User\Handler;

use GC\Home\Handler\HomeHandler;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserLogoutHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'user.logout';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->getAttributeBag()->clear();

        return $this->redirect(HomeHandler::NAME);
    }
}