<?php

declare(strict_types=1);

namespace GC\Alliance\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\HandlerAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AllianceApplicationApproveHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'alliance.application.approve';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->redirectRoute(AllianceApplicationsHandler::NAME);
    }
}