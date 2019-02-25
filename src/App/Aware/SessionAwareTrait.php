<?php

namespace GC\App\Aware;

use GC\App\Dependency\SingletonContainer;
use Inferno\Session\Bag\AttributeBagInterface;
use Inferno\Session\Bag\FlashBagInterface;
use Inferno\Session\Manager\SessionManagerInterface;
use Pimple\Container;

trait SessionAwareTrait
{
    /**
     * @return \Pimple\Container
     */
    private function getContainer(): Container
    {
        return SingletonContainer::getContainer();
    }

    /**
     * @return \Inferno\Session\Bag\AttributeBagInterface
     */
    protected function getAttributeBag(): AttributeBagInterface
    {
        return $this->getContainer()->offsetGet(SessionManagerInterface::class)->getAttributeBag();
    }

    /**
     * @return \Inferno\Session\Bag\FlashBagInterface
     */
    protected function getFlashBag(): FlashBagInterface
    {
        return $this->getContainer()->offsetGet(SessionManagerInterface::class)->getFlashBag();
    }
}