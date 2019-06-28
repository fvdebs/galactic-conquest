<?php

declare(strict_types=1);

namespace GC\Combat\Exception;

use Exception;

use function sprintf;

class FleetNotFoundException extends Exception implements CombatExceptionInterface
{
    /**
     * @param int $unitId
     *
     * @return \GC\Combat\Exception\FleetNotFoundException
     */
    public static function fromFleetId(int $unitId): FleetNotFoundException
    {
        $message = sprintf('Fleet with the given id "%s" not found.', $unitId);

        return new static($message);
    }
}
