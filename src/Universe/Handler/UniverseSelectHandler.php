<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UniverseSelectHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'universe.select';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Universe/universeSelect.twig');
    }
}