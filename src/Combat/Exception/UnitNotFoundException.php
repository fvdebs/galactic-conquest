<?php

declare(strict_types=1);

namespace GC\Combat\Exception;

use Exception;

use function sprintf;

class UnitNotFoundException extends Exception implements CombatExceptionInterface
{
    /**
     * @param int $unitId
     *
     * @return \GC\Combat\Exception\UnitNotFoundException
     */
    public static function fromUnitId(int $unitId): UnitNotFoundException
    {
        $message = sprintf('Unit with the given id "%s" not found.', $unitId);

        return new static($message);
    }
}
