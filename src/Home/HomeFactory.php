<?php

declare(strict_types=1);

namespace GC\Home;

use GC\Home\Handler\HomeHandler;
use GC\User\Model\UserRepository;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Renderer\RendererInterface;
use Psr\Http\Server\RequestHandlerInterface;


final class HomeFactory
{
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
     * @param ResponseFactoryInterface $responseFactory
     * @param RendererInterface $renderer
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
     * @return \Psr\Http\Server\RequestHandlerInterface
     */
    public function createHomeHandler(): RequestHandlerInterface
    {
        return new HomeHandler($this->responseFactory, $this->renderer, $this->userRepository);
    }
}
