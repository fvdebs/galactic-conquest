<?php

declare(strict_types=1);

namespace GC\App\Http;

use Fig\Http\Message\RequestMethodInterface;
use Inferno\Http\Response\ErrorResponseFactoryInterface;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Renderer\RendererInterface;
use Inferno\Routing\Exception\RouteNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use \Throwable;

final class ErrorResponseFactory implements ErrorResponseFactoryInterface
{
    /**
     * @var bool
     */
    private $catchErrors;

    /**
     * @var \Inferno\Http\Response\ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var \Psr\Http\Message\UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var \Inferno\Renderer\RendererInterface $templateRenderer
     */
    private $renderer;

    /**
     * @param bool $catchErrors
     * @param \Inferno\Http\Response\ResponseFactoryInterface $responseFactory
     * @param \Psr\Http\Message\UriFactoryInterface $uriFactory
     * @param \Inferno\Renderer\RendererInterface $renderer
     */
    public function __construct(
        bool $catchErrors,
        ResponseFactoryInterface $responseFactory,
        UriFactoryInterface $uriFactory,
        RendererInterface $renderer
    ) {
        $this->catchErrors = $catchErrors;
        $this->responseFactory = $responseFactory;
        $this->uriFactory = $uriFactory;
        $this->renderer = $renderer;
    }

    /**
     * @param \Throwable $throwable
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \Throwable
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createFromThrowable(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
    {
        if ($this->catchErrors === false) {
            throw $throwable;
        }

        if ($request->getMethod() ===  RequestMethodInterface::METHOD_POST) {
            return $this->responseFactory->createFromContent(
                ['isSuccess' => false, 'message' =>  $this->uriFactory->createUri('/')]
            );
        }

        if ($throwable instanceof RouteNotFoundException) {
            return $this->responseFactory->createFromContent(
                $this->renderer->render('@App/error/not-found.twig'),
                404
            );
        }

        return $this->responseFactory->createFromContent(
            $this->renderer->render('@App/error/service-unavailable.twig'),
            503
        );
    }

    /**
     * @param \Throwable $throwable
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \Throwable
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
    {
        return $this->createFromThrowable($throwable, $request);
    }
}
