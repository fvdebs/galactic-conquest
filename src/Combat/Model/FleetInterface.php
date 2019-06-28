<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface FleetInterface
{
    /**
     * @return int
     */
    public function getFleetId(): int;

    /**
     * @return string[]
     */
    public function getData(): array;

    /**
     * @return int[]
     */
    public function getUnits(): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasDataValue(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getDataValue(string $key);

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    public function destroyUnit(int $unitId, float $quantity): void;

    /**
     * @return float[]
     */
    public function getUnitsLost(): array;

    /**
     * @return bool
     */
    public function hasUnitsDestroyed(): bool;

    /**
     * @return float[]
     */
    public function getUnitsDestroyed(): array;

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    public function addUnitDestroyedQuantity(int $unitId, float $quantity): void;

    /**
     * @param int $unitId
     *
     * @return float
     */
    public function getQuantityOf(int $unitId): float;

    /**
     * @return float
     */
    public function getSalvagedMetal(): float;

    /**
     * @param float $salvagedMetal
     *
     * @return void
     */
    public function setSalvagedMetal(float $salvagedMetal): void;

    /**
     * @return float
     */
    public function getSalvagedCrystal(): float;

    /**
     * @param float $salvagedCrystal
     *
     * @return void
     */
    public function setSalvagedCrystal(float $salvagedCrystal): void;

    /**
     * @return float
     */
    public function getExtractorStolenMetal(): float;

    /**
     * @param float $extractorStolenMetal
     *
     * @return void
     */
    public function setExtractorStolenMetal(float $extractorStolenMetal): void;

    /**
     * @return float
     */
    public function getExtractorStolenCrystal(): float;

    /**
     * @param float $extractorStolenCrystal
     *
     * @return void
     */
    public function setExtractorStolenCrystal(float $extractorStolenCrystal): void;

    /**
     * @return float
     */
    public function getExtractorsProtected(): float;

    /**
     * @param float $extractorsProtected
     *
     * @return void
     */
    public function setExtractorsProtected(float $extractorsProtected): void;

    /**
     * @return float
     */
    public function getExtractorsStealCapacity(): float;

    /**
     * @param float $extractorsStealCapacity
     *
     * @return void
     */
    public function setExtractorsStealCapacity(float $extractorsStealCapacity): void;

    /**
     * @param float $extractorsStealCapacity
     *
     * @return void
     */
    public function decreaseExtractorsStealCapacity(float $extractorsStealCapacity): void;

    /**
     * @return float[]
     */
    public function getInsufficientCarrierCapacityLosses(): array;

    /**
     * @return bool
     */
    public function hasInsufficientCarrierCapacityLosses(): bool;

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    public function addInsufficientCarrierCapacityLosses(int $unitId, float $quantity): void;

    /**
     * @return float
     */
    public function getCarrierCapacity(): float;

    /**
     * @param float $carrierCapacity
     *
     * @return void
     */
    public function setCarrierCapacity(float $carrierCapacity): void;

    /**
     * @return float
     */
    public function getCarrierCapacityConsumed(): float;

    /**
     * @param float $carrierCapacityConsumed
     *
     * @return void
     */
    public function setCarrierCapacityConsumed(float $carrierCapacityConsumed): void;

    /**
     * @return void
     */
    public function floorUnitQuantities(): void;

    /**
     * @return void
     */
    public function __clone();
}
