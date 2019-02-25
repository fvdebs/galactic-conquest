<?php

declare(strict_types=1);

namespace GC\Home\Handler;

use GC\User\Model\UserRepository;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Renderer\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HomeHandler implements RequestHandlerInterface
{
    public const NAME = 'home';

    /**
     * @var \Inferno\Http\Response\ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var \Inferno\Renderer\RendererInterface
     */
    private $renderer;

    /**
     * @var \GC\User\Model\UserRepository
     */
    private $userRepository;

    /**
     * @param \Inferno\Http\Response\ResponseFactoryInterface $responseFactory
     * @param \Inferno\Renderer\RendererInterface $renderer
     * @param \GC\User\Model\UserRepository $userRepository
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        RendererInterface $renderer,
        UserRepository $userRepository
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->userRepository = $userRepository;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $userCount = $this->userRepository->countUsers();
        } catch (\Throwable $throwable) {
            $userCount = 0;
        }

        return $this->responseFactory->createFromContent(
            $this->renderer->render('@Home/home.twig', [
                'userCount' => $userCount,
            ])
        );
    }
}
