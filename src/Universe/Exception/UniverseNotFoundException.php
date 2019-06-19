<?php

declare(strict_types=1);

namespace GC\Universe\Exception;

use function sprintf;

class UniverseNotFoundException extends \Exception implements UniverseExceptionInterface
{
    /**
     * @param int $universeId
     *
     * @return \GC\Universe\Exception\UniverseNotFoundException
     */
    public static function fromUniverseId(int $universeId): UniverseNotFoundException
    {
        $message = sprintf('Universe with the given universeId "%s" not found.', $universeId);

        return new static($message);
    }

    /**
     * @param mixed $universeId
     *
     * @return \GC\Universe\Exception\UniverseNotFoundException
     */
    public static function fromInvalidUniverseId($universeId): UniverseNotFoundException
    {
        $message = sprintf('The given universe id "%s" is not a valid integer.', (string) $universeId);

        return new static($message);
    }
}
