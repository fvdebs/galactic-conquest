<?php

declare(strict_types=1);

namespace GC\Tick\Exception;

use Exception;

use function sprintf;

class UniverseNotFoundException extends Exception implements TickExceptionInterface
{
    /**
     * @param int $universeId
     *
     * @return \GC\Tick\Exception\UniverseNotFoundException
     */
    public static function fromUniverseId(int $universeId): UniverseNotFoundException
    {
        $message = sprintf('Universe with the given universeId "%s" not found.', $universeId);

        return new static($message);
    }
}
