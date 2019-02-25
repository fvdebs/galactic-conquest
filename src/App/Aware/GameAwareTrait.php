<?php

namespace GC\App\Aware;

use GC\App\Dependency\SingletonContainer;
use GC\Player\Model\Player;
use GC\Universe\Model\Universe;
use Pimple\Container;

trait GameAwareTrait
{
    /**
     * @return \Pimple\Container
     */
    private function getContainer(): Container
    {
        return SingletonContainer::getContainer();
    }

    /**
     * @return \GC\Player\Model\Player
     */
    protected function getPlayer(): Player
    {
        return $this->getContainer()->offsetGet(Player::class);
    }

    /**
     * @return \GC\Universe\Model\Universe
     */
    protected function getUniverse(): Universe
    {
        return $this->getContainer()->offsetGet(Universe::class);
    }
}