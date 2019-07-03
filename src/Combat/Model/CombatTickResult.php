<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use function array_key_exists;

final class CombatTickResult implements CombatTickResultInterface
{
    /**
     * @var int[]
     */
    private $destroyedUnit = [];

    /**
     * @var int[][]
     */
    private $destroyedByUnit = [];

    /**
     * @return int[]
     */
    public function getDestroyedUnit(): array
    {
        return $this->destroyedUnit;
    }

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    public function addDestroyedUnit(int $unitId, float $quantity): void
    {
        if (!$this->hasDestroyedUnit($unitId)) {
            $this->destroyedUnit[$unitId] = 0.0;
        }

        $this->destroyedUnit[$unitId] += $quantity;
    }

    /**
     * @param int $unitId
     *
     * @return bool
     */
    public function hasDestroyedUnit(int $unitId): bool
    {
        return array_key_exists($unitId, $this->destroyedUnit);
    }

    /**
     * @param int $unitId
     *
     * @return float
     */
    public function getDestroyedUnitQuantityOf(int $unitId): float
    {
        if ($this->hasDestroyedUnit($unitId)) {
            return $this->destroyedUnit[$unitId];
        }

        return 0;
    }

    /**
     * @return int[]
     */
    public function getDestroyedByUnit(): array
    {
        return $this->destroyedByUnit;
    }

    /**
     * @param int $attackingUnitId
     * @param int $targetUnitId
     * @param float $quantity
     *
     * @return void
     */
    public function addDestroyedByUnit(int $attackingUnitId, int $targetUnitId, float $quantity): void
    {
        if (!array_key_exists($attackingUnitId, $this->destroyedByUnit) ||
            !array_key_exists($targetUnitId, $this->destroyedByUnit[$attackingUnitId])) {

            $this->destroyedByUnit[$attackingUnitId][$targetUnitId] = 0.0;
        }

        $this->destroyedByUnit[$attackingUnitId][$targetUnitId] += $quantity;
    }
}
