<?php

declare(strict_types=1);

namespace GC\Alliance\Handler;

use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AlliancePublicOverviewHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'alliance.public.overview';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Alliance/alliancePublicOverview.twig');
    }
}