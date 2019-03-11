<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use GC\App\Aware\GameAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UniverseRegisterHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;

    public const NAME = 'universe.register';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $universe = $this->getCurrentUniverse($request);
        $user = $this->getCurrentUser($request);

        if ($user->hasPlayerIn($universe)) {
            return $this->redirect(UniverseSelectHandler::NAME);
        }

        return $this->render('@Universe/universe-register.twig', [
            'universe' => $universe,
        ]);
    }
}