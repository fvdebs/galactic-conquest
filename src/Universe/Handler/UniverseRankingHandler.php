<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UniverseRankingHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'universe.ranking';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Universe/universeRanking.twig');
    }
}