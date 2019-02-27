<?php

namespace GC\App\Aware;

use GC\App\Dependency\SingletonContainer;
use GC\Player\Model\Player;
use GC\Universe\Model\Universe;
use GC\User\Model\User;

trait GameAwareTrait
{
    /**
     * @return \GC\Player\Model\Player
     */
    protected function getCurrentPlayer(): Player
    {
        return SingletonContainer::getContainer()->offsetGet(Player::class);
    }

    /**
     * @return \GC\Universe\Model\Universe
     */
    protected function getCurrentUniverse(): Universe
    {
        return SingletonContainer::getContainer()->offsetGet(Universe::class);
    }

    /**
     * @return \GC\User\Model\User
     */
    protected function getCurrentUser(): User
    {
        return SingletonContainer::getContainer()->offsetGet(User::class);
    }
}