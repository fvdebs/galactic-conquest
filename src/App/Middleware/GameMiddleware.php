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
use Inferno\Routing\UrlGenerator\UrlGenerator;
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
     * @var \Inferno\Routing\UrlGenerator\UrlGenerator
     */
    private $urlGenerator;

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
     * @param \Inferno\Routing\UrlGenerator\UrlGenerator $urlGenerator
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
        UrlGenerator $urlGenerator,
        Container $container
    ) {
        $this->sessionManager = $sessionManager;
        $this->universeRepository = $universeRepository;
        $this->playerRepository = $playerRepository;
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->uriFactory = $uriFactory;
        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->container = $container;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Inferno\Routing\Exception\ResourceNotFoundException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //////////////// UNIVERSE ////////////////
        $pathChunks = explode('/', $request->getUri()->getPath());
        if (\count($pathChunks) < 2) {
            return $handler->handle($request);
        }

        // if universe is not found execute next middleware
        $universe = $this->universeRepository->findByName($pathChunks[2]);
        if ($universe === null) {
            return $handler->handle($request);
        }

        // add current universe to container and twig
        $this->container->offsetSet(Universe::class, $universe);
        $this->twig->addGlobal('universe', $universe);

        //////////////// USER ////////////////
        // redirect to home if user is not logged in
        $user = $this->userRepository->findById($this->sessionManager->getAttributeBag()->get(User::SESSION_KEY_USER_ID));
        if ($user === null) {
            return $this->createRedirect(HomeHandler::NAME);
        }

        $this->container->offsetSet(User::class, $user);
        $this->twig->addGlobal('user', $user);

        //////////////// PLAYER ////////////////
        // redirect to universe selection if player is not found
        $player = $this->playerRepository->findByUserIdAndUniverseId($user->getUserId(), $universe->getUniverseId());
        if ($player === null) {
            return $this->createRedirect('universe.select');
        }

        // add current player to container and twig
        $this->container->offsetSet(Player::class, $player);
        $this->twig->addGlobal('player', $player);

        return $handler->handle($request);
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
                $this->urlGenerator->generate($name)
            )
        );
    }
}