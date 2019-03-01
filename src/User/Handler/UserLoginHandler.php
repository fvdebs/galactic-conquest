<?php

declare(strict_types=1);

namespace GC\User\Handler;

use GC\App\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserLoginHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'user.login';
    public const SESSION_KEY_USER_ID = 'userId';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@User/userLogin.twig');
    }
}