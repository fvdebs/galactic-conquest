<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UniverseSelectHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'universes';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Universe/universes.twig', [
            'universes' => $this->getUniverseRepository()->findAll(),
        ]);
    }
}