<?php

declare(strict_types=1);

namespace GC\Universe\Exception;

class RegisterUniversePrivateGalaxyPasswordFailedException extends \Exception implements UniverseExceptionInterface
{
    /**
     * @param string $password
     *
     * @return \GC\Universe\Exception\RegisterUniversePrivateGalaxyPasswordFailedException
     */
    public static function forPassword(string $password): RegisterUniversePrivateGalaxyPasswordFailedException
    {
        $message = sprintf('Galaxy with Password "%s" not found.', $password);

        return new static($message);
    }
}
