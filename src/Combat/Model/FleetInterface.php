<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface FleetInterface
{
    /**
     * @return int
     */
    public function getFleetReference(): int;

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
     * @return int[]
     */
    public function getUnitsLost(): array;

    /**
     * @return bool
     */
    public function hasUnitsDestroyed(): bool;

    /**
     * @return array
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
     * @return int
     */
    public function getSalvageMetal(): int;

    /**
     * @param int $salvageMetal
     *
     * @return void
     */
    public function setSalvageMetal(int $salvageMetal): void;

    /**
     * @return int
     */
    public function getSalvageCrystal(): int;

    /**
     * @param int $salvageCrystal
     *
     * @return void
     */
    public function setSalvageCrystal(int $salvageCrystal): void;

    /**
     * @return int
     */
    public function getExtractorStolenMetal(): int;

    /**
     * @param int $extractorStolenMetal
     *
     * @return void
     */
    public function setExtractorStolenMetal(int $extractorStolenMetal): void;

    /**
     * @return int
     */
    public function getExtractorStolenCrystal(): int;

    /**
     * @param int $extractorStolenCrystal
     *
     * @return void
     */
    public function setExtractorStolenCrystal(int $extractorStolenCrystal): void;

    /**
     * @return int
     */
    public function getExtractorsGuarded(): int;

    /**
     * @param int $extractorsGuarded
     *
     * @return void
     */
    public function setExtractorsGuarded(int $extractorsGuarded): void;

    /**
     * @return int
     */
    public function getExtractorsStealCapacity(): int;

    /**
     * @param int $extractorsStealCapacity
     */
    public function setExtractorsStealCapacity(int $extractorsStealCapacity): void;

    /**
     * @param int $number
     *
     * @return void
     */
    public function decreaseExtractorsStealCapacity(int $number): void;

    /**
     * @return int[]
     */
    public function getInsufficientCarrierLosses(): array;

    /**
     * @param int $unitId
     * @param int $quantity
     *
     * @return void
     */
    public function addInsufficientCarrierLosses(int $unitId, int $quantity): void;

    /**
     * @return int
     */
    public function getCarrierSpace(): int;

    /**
     * @param int $carrierSpace
     *
     * @return void
     */
    public function setCarrierSpace(int $carrierSpace): void;

    /**
     * @return int
     */
    public function getCarrierConsumption(): int;

    /**
     * @param int $carrierConsumption
     *
     * @return void
     */
    public function setCarrierConsumption(int $carrierConsumption): void;

    /**
     * @return void
     */
    public function normalize(): void;

    /**
     * @return void
     */
    public function __clone();
}
