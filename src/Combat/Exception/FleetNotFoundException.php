<?php

declare(strict_types=1);

namespace GC\Combat\Exception;

use Exception;

use function sprintf;

class FleetNotFoundException extends Exception implements CombatExceptionInterface
{
    /**
     * @param mixed $fleetReference
     *
     * @return \GC\Combat\Exception\FleetNotFoundException
     */
    public static function fromFleetReference($fleetReference): FleetNotFoundException
    {
        $message = sprintf('Fleet with the given reference "%s" not found.', $fleetReference);

        return new static($message);
    }
}
