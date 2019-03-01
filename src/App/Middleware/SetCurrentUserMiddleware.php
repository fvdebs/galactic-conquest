<?php

declare(strict_types=1);

namespace GC\App\Middleware;

use GC\User\Handler\UserLoginHandler;
use GC\User\Model\UserRepository;
use Inferno\Session\Manager\SessionManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SetCurrentUserMiddleware implements MiddlewareInterface
{
    public const REQUEST_ATTRIBUTE_CURRENT_USER = '@currentUser';

    /**
     * @var \Inferno\Session\Manager\SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var \GC\User\Model\UserRepository
     */
    private $userRepository;

    /**
     * @param \Inferno\Session\Manager\SessionManagerInterface $sessionManager
     * @param \GC\User\Model\UserRepository $userRepository
     */
    public function __construct(SessionManagerInterface $sessionManager, UserRepository $userRepository)
    {
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->userRepository->findById(
            $this->sessionManager->getAttributeBag()->get(UserLoginHandler::SESSION_KEY_USER_ID)
        );

        if ($user === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute(static::REQUEST_ATTRIBUTE_CURRENT_USER, $user);

        return $handler->handle($request);
    }
}