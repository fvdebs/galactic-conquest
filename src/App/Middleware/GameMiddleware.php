<?php

declare(strict_types=1);

namespace GC\App\Middleware;

use GC\Home\Handler\HomeHandler;
use GC\Player\Model\Player;
use GC\Player\Model\PlayerRepository;
use GC\Universe\Model\Universe;
use GC\Universe\Model\UniverseRepository;
use GC\User\Model\User;
use GC\User\Model\UserRepository;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Routing\Router\RouterInterface;
use Inferno\Session\Manager\SessionManagerInterface;
use Pimple\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig_Environment;

final class GameMiddleware implements MiddlewareInterface
{
    /**
     * @var \Inferno\Session\Manager\SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var \GC\User\Model\UserRepository
     */
    private $userRepository;

    /**
     * @var \GC\Universe\Model\UniverseRepository
     */
    private $universeRepository;

    /**
     * @var \GC\Player\Model\PlayerRepository
     */
    private $playerRepository;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Psr\Http\Message\UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var \Inferno\Http\Response\ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var \Inferno\Routing\Router\RouterInterface
     */
    private $router;

    /**
     * @var \Pimple\Container
     */
    private $container;

    /**
     * @param \Inferno\Session\Manager\SessionManagerInterface $sessionManager
     * @param \GC\User\Model\UserRepository $userRepository
     * @param \GC\Universe\Model\UniverseRepository $universeRepository
     * @param \GC\Player\Model\PlayerRepository $playerRepository
     * @param \Twig_Environment $twig
     * @param \Psr\Http\Message\UriFactoryInterface $uriFactory
     * @param \Inferno\Http\Response\ResponseFactoryInterface $responseFactory
     * @param \Inferno\Routing\Router\RouterInterface $router
     * @param \Pimple\Container $container
     */
    public function __construct(
        SessionManagerInterface $sessionManager,
        UserRepository $userRepository,
        UniverseRepository $universeRepository,
        PlayerRepository $playerRepository,
        Twig_Environment $twig,
        UriFactoryInterface $uriFactory,
        ResponseFactoryInterface $responseFactory,
        RouterInterface $router,
        Container $container
    ) {
        $this->sessionManager = $sessionManager;
        $this->universeRepository = $universeRepository;
        $this->playerRepository = $playerRepository;
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->uriFactory = $uriFactory;
        $this->responseFactory = $responseFactory;
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @throws \Inferno\Routing\Exception\ResourceNotFoundException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->getCurrentUser();

        $universeName = $request->getAttribute('universe');
        if ($request->getAttribute('universe') === null) {
            return $handler->handle($request);
        }

        $universe = $this->getUniverseByName($universeName);
        if ($universe === null || $user === null) {
            return $this->createRedirect(HomeHandler::NAME);
        }

        $player = $this->getCurrentPlayer($user, $universe);
        if ($player === null) {
            return $this->createRedirect('universe.select');
        }

        return $handler->handle($request);
    }

    /**
     * @return \GC\User\Model\User|null
     */
    private function getCurrentUser(): ?User
    {
        $userId = $this->sessionManager->getAttributeBag()->get(User::SESSION_KEY_USER_ID);
        if (! \is_int($userId)) {
            return null;
        }

        $user = $this->userRepository->findById($userId);
        if ($user !== null) {
            $this->container->offsetSet(User::class, $user);
            $this->twig->addGlobal('user', $user);
        }

        return $user;
    }

    /**
     * @param string $universeName
     *
     * @return \GC\Universe\Model\Universe|null
     */
    private function getUniverseByName(string $universeName): ?Universe
    {
        $universe = $this->universeRepository->findByName($universeName);
        if ($universe !== null) {
            $this->container->offsetSet(Universe::class, $universe);
            $this->twig->addGlobal('universe', $universe);
        }

        return $universe;
    }

    /**
     * @param \GC\User\Model\User|null $user
     * @param \GC\Universe\Model\Universe|null $universe
     *
     * @return \GC\Player\Model\Player|null
     */
    private function getCurrentPlayer(?User $user, ?Universe $universe): ?Player
    {
        if ($user === null || $universe === null) {
            return null;
        }

        $player = $this->playerRepository->findByUserIdAndUniverseId($user->getUserId(), $universe->getUniverseId());
        if ($player !== null) {
            $this->container->offsetSet(Player::class, $player);
            $this->twig->addGlobal('player', $player);
        }

        return $player;
    }

    /**
     * @param string $name
     *
     * @throws \Inferno\Routing\Exception\ResourceNotFoundException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function createRedirect(string $name): ResponseInterface
    {
        return $this->responseFactory->createFromContent(
            $this->uriFactory->createUri(
                $this->router->generate($name)
            )
        );
    }
}