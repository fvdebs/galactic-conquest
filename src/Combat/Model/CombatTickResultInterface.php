<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface CombatTickResultInterface
{
    /**
     * @return int[]
     */
    public function getDestroyedUnit(): array;

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    public function addDestroyedUnit(int $unitId, float $quantity): void;

    /**
     * @param int $unitId
     *
     * @return bool
     */
    public function hasDestroyedUnit(int $unitId): bool;

    /**
     * @param int $unitId
     *
     * @return float
     */
    public function getDestroyedUnitQuantityOf(int $unitId): float;

    /**
     * @return int[]
     */
    public function getDestroyedByUnit(): array;

    /**
     * @param int $attackingUnitId
     * @param int $targetUnitId
     * @param float $quantity
     *
     * @return void
     */
    public function addDestroyedByUnit(int $attackingUnitId, int $targetUnitId, float $quantity): void;
}
