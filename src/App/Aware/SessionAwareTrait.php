<?php

namespace GC\App\Aware;

use GC\App\Dependency\SingletonContainer;
use Inferno\Session\Bag\AttributeBagInterface;
use Inferno\Session\Bag\FlashBagInterface;
use Inferno\Session\Manager\SessionManagerInterface;

trait SessionAwareTrait
{
    /**
     * @return \Inferno\Session\Bag\AttributeBagInterface
     */
    protected function getAttributeBag(): AttributeBagInterface
    {
        return SingletonContainer::getContainer()->offsetGet(SessionManagerInterface::class)->getAttributeBag();
    }

    /**
     * @return \Inferno\Session\Bag\FlashBagInterface
     */
    protected function getFlashBag(): FlashBagInterface
    {
        return SingletonContainer::getContainer()->offsetGet(SessionManagerInterface::class)->getFlashBag();
    }
}