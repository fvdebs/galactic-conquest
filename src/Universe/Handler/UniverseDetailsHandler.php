<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use GC\App\Aware\GameAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UniverseDetailsHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;

    public const NAME = 'universe.details';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Universe/universe-details.twig', [
            'universe' => $this->getCurrentUniverse($request),
        ]);
    }
}