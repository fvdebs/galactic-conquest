<?php

declare(strict_types=1);

namespace GC\Home\Handler;

use Inferno\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomeHandler implements RequestHandlerInterface
{
    /**
     * @var \Inferno\Template\TemplateRendererInterface
     */
    protected $renderer;

    /**
     * @param \Inferno\Template\TemplateRendererInterface $renderer
     */
    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->renderer->renderResponse('@Home/home.twig');
    }
}
