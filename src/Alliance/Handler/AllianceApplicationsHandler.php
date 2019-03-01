<?php

declare(strict_types=1);

namespace GC\Alliance\Handler;

use GC\App\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AllianceApplicationsHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'alliance.applications';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Alliance/allianceApplications.twig');
    }
}