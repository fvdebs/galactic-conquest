<?php

declare(strict_types=1);

namespace Acme\Home;

use Inferno\Application\Factory\AbstractFactory;
use Inferno\TwigBridge\TwigRenderer;
use Psr\Http\Server\RequestHandlerInterface;

class HomeFactory extends AbstractFactory
{
    /**
     * @return \Psr\Http\Server\RequestHandlerInterface
     */
    public function createHomeHandler(): RequestHandlerInterface
    {
        return new HomeHandler(
            $this->getContainer()->get(TwigRenderer::class)
        );
    }
}
