<?php

declare(strict_types=1);

namespace GC\Universe\Exception;

use GC\Galaxy\Model\Galaxy;

class RegisterUniversePrivateGalaxyFullException extends \Exception implements UniverseExceptionInterface
{
    /**
     * @param \GC\Galaxy\Model\Galaxy
     *
     * @return \GC\Universe\Exception\RegisterUniversePrivateGalaxyFullException
     */
    public static function forGalaxy(Galaxy $galaxy): RegisterUniversePrivateGalaxyFullException
    {
        $message = sprintf('Galaxy "%s" has not enough space for a new Member.', $galaxy->getNumber());

        return new static($message);
    }
}
