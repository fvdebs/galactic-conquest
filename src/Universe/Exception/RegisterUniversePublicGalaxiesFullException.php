<?php

declare(strict_types=1);

namespace GC\Universe\Exception;

class RegisterUniversePublicGalaxiesFullException extends \Exception implements UniverseExceptionInterface
{
    /**
     * @param \GC\Galaxy\Model\Galaxy
     *
     * @return \GC\Universe\Exception\RegisterUniversePublicGalaxiesFullException
     */
    public static function forFull(): RegisterUniversePublicGalaxiesFullException
    {
        return new static('Currently there is no public galaxy with enough space for a new player.');
    }
}
