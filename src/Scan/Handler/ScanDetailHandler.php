<?php

declare(strict_types=1);

namespace GC\Scan\Handler;

use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ScanDetailHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;

    public const NAME = 'scan.detail';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('@Scan/scanDetail.twig');
    }
}