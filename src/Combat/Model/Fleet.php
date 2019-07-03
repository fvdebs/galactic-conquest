<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use GC\Combat\Exception\UnitNotFoundException;

use function array_key_exists;
use function count;
use function is_object;
use function is_array;
use function unserialize;
use function serialize;

final class Fleet implements FleetInterface
{
    /**
     * @var int
     */
    private $fleetId;

    /**
     * @var float[]
     */
    private $units;

    /**
     * @var string[]
     */
    private $data;

    /**
     * @var bool
     */
    private $isTarget = false;

    /**
     * @var float[]
     */
    private $unitsLost = [];

    /**
     * @var float[]
     */
    private $unitsDestroyed = [];

    /**
     * @var float
     */
    private $extractorStolenMetal = 0.0;

    /**
     * @var float
     */
    private $extractorStolenCrystal = 0.0;

    /**
     * @var float
     */
    private $salvagedMetal = 0.0;

    /**
     * @var float
     */
    private $salvagedCrystal = 0.0;

    /**
     * @var float
     */
    private $extractorsProtected = 0.0;

    /**
     * @var float
     */
    private $extractorsStealCapacity = 0.0;

    /**
     * @var float[]
     */
    private $insufficientCarrierCapacityLosses = [];

    /**
     * @var float
     */
    private $carrierCapacity = 0.0;

    /**
     * @var float
     */
    private $carrierCapacityConsumed = 0.0;

    /**
     * @param int $fleetId
     * @param float[] $units - unitId => quantity
     * @param string[] $data
     */
    public function __construct(int $fleetId, array $units = [], array $data = [])
    {
        $this->fleetId = $fleetId;
        $this->units = $this->clearEmptyAndRemoveNonNumericValues($units);
        $this->data = $data;
    }

    /**
     * @param string[] $unitData
     *
     * @return int[]
     */
    private function clearEmptyAndRemoveNonNumericValues(array $unitData): array
    {
        foreach ($unitData as $unitId => $quantity) {
            $quantity = (float) $quantity;
            if (!is_numeric($quantity) || $quantity === 0.0) {
                unset($unitData[$unitId]);
            }

            $unitData[$unitId] = (float) $quantity;
        }

        return $unitData;
    }

    /**
     * @return int
     */
    public function getFleetId(): int
    {
        return $this->fleetId;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return float[]
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * @return bool
     */
    public function getUnitsLeft(): bool
    {
        foreach ($this->units as $unitId => $quantity) {
            if ($quantity > 0.0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasDataValue(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getDataValue(string $key)
    {
        if ($this->hasDataValue($key)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    public function destroyUnit(int $unitId, float $quantity): void
    {
        $this->decreaseUnitQuantity($unitId, $quantity);

        if (!array_key_exists($unitId, $this->unitsLost)) {
            $this->unitsLost[$unitId] = 0.0;
        }

        $this->unitsLost[$unitId] += $quantity;
    }

    /**
     * @return float[]
     */
    public function getUnitsLost(): array
    {
        return $this->unitsLost;
    }

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    private function decreaseUnitQuantity(int $unitId, float $quantity): void
    {
        if (!$this->hasUnit($unitId)) {
            throw UnitNotFoundException::fromUnitId($unitId);
        }

        $this->units[$unitId] -= $quantity;
    }

    /**
     * @param int $unitId
     *
     * @return float
     */
    public function getQuantityOf(int $unitId): float
    {
        $quantity = 0.0;
        if ($this->hasUnit($unitId)) {
            $quantity = $this->units[$unitId];
        }

        return (float) $quantity;
    }

    /**
     * @return bool
     */
    public function hasUnitsDestroyed(): bool
    {
        return count($this->unitsDestroyed) > 0;
    }

    /**
     * @return float[]
     */
    public function getUnitsDestroyed(): array
    {
        return $this->unitsDestroyed;
    }

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    public function addUnitDestroyedQuantity(int $unitId, float $quantity): void
    {
        if (!array_key_exists($unitId, $this->unitsDestroyed)) {
            $this->unitsDestroyed[$unitId] = 0.0;
        }

        $this->unitsDestroyed[$unitId] += $quantity;
    }

    /**
     * @param int $unitId
     *
     * @return bool
     */
    private function hasUnit(int $unitId): bool
    {
        return array_key_exists($unitId, $this->units);
    }

    /**
     * @return float
     */
    public function getSalvagedMetal(): float
    {
        return $this->salvagedMetal;
    }

    /**
     * @param float $salvagedMetal
     *
     * @return void
     */
    public function setSalvagedMetal(float $salvagedMetal): void
    {
        $this->salvagedMetal = $salvagedMetal;
    }

    /**
     * @return float
     */
    public function getSalvagedCrystal(): float
    {
        return $this->salvagedCrystal;
    }

    /**
     * @param float $salvagedCrystal
     *
     * @return void
     */
    public function setSalvagedCrystal(float $salvagedCrystal): void
    {
        $this->salvagedCrystal = $salvagedCrystal;
    }

    /**
     * @return float
     */
    public function getExtractorStolenMetal(): float
    {
        return $this->extractorStolenMetal;
    }

    /**
     * @param float $extractorStolenMetal
     *
     * @return void
     */
    public function setExtractorStolenMetal(float $extractorStolenMetal): void
    {
        $this->extractorStolenMetal = $extractorStolenMetal;
    }

    /**
     * @return float
     */
    public function getExtractorStolenCrystal(): float
    {
        return $this->extractorStolenCrystal;
    }

    /**
     * @param float $extractorStolenCrystal
     *
     * @return void
     */
    public function setExtractorStolenCrystal(float $extractorStolenCrystal): void
    {
        $this->extractorStolenCrystal = $extractorStolenCrystal;
    }

    /**
     * @return float
     */
    public function getExtractorsProtected(): float
    {
        return $this->extractorsProtected;
    }

    /**
     * @param float $extractorsProtected
     *
     * @return void
     */
    public function setExtractorsProtected(float $extractorsProtected): void
    {
        $this->extractorsProtected = $extractorsProtected;
    }

    /**
     * @return float
     */
    public function getExtractorsStealCapacity(): float
    {
        return $this->extractorsStealCapacity;
    }

    /**
     * @param float $extractorsStealCapacity
     *
     * @return void
     */
    public function setExtractorsStealCapacity(float $extractorsStealCapacity): void
    {
        $this->extractorsStealCapacity = $extractorsStealCapacity;
    }

    /**
     * @param float $capacity
     *
     * @return void
     */
    public function decreaseExtractorsStealCapacity(float $capacity): void
    {
        $this->extractorsStealCapacity -= $capacity;
    }

    /**
     * @return float[]
     */
    public function getInsufficientCarrierCapacityLosses(): array
    {
        return $this->insufficientCarrierCapacityLosses;
    }

    /**
     * @return bool
     */
    public function hasInsufficientCarrierCapacityLosses(): bool
    {
        return count($this->insufficientCarrierCapacityLosses) > 0;
    }

    /**
     * @param int $unitId
     * @param float $quantity
     *
     * @return void
     */
    public function addInsufficientCarrierCapacityLosses(int $unitId, float $quantity): void
    {
        if (!array_key_exists($unitId, $this->insufficientCarrierCapacityLosses)) {
            $this->insufficientCarrierCapacityLosses[$unitId] = 0.0;
        }

        $this->insufficientCarrierCapacityLosses[$unitId] += $quantity;
    }

    /**
     * @return float
     */
    public function getCarrierCapacity(): float
    {
        return $this->carrierCapacity;
    }

    /**
     * @param float $carrierCapacity
     *
     * @return void
     */
    public function setCarrierCapacity(float $carrierCapacity): void
    {
        $this->carrierCapacity = $carrierCapacity;
    }

    /**
     * @return float
     */
    public function getCarrierCapacityConsumed(): float
    {
        return $this->carrierCapacityConsumed;
    }

    /**
     * @param float $carrierCapacityConsumed
     *
     * @return void
     */
    public function setCarrierCapacityConsumed(float $carrierCapacityConsumed): void
    {
        $this->carrierCapacityConsumed = $carrierCapacityConsumed;
    }

    /**
     * @return void
     */
    public function normalizeUnitQuantities(): void
    {
        $this->units = $this->normalizeArrayValues($this->units);
        $this->unitsLost = $this->normalizeArrayValues($this->unitsLost);
        $this->unitsDestroyed = $this->normalizeArrayValues($this->unitsDestroyed);
    }

    /**
     * @param float[] $array
     *
     * @return float[]
     */
    private function normalizeArrayValues(array $array): array
    {
        $rounded = [];

        foreach ($array as $index => $value) {
            $rounded[$index] = round($value);
        }

        return $rounded;
    }

    /**
     * @return bool
     */
    public function isTarget(): bool
    {
        return $this->isTarget;
    }

    /**
     * @param bool $isTarget
     *
     * @return void
     */
    public function setIsTarget(bool $isTarget): void
    {
        $this->isTarget = $isTarget;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        foreach($this as $key => $val) {
            if (is_object($val) || is_array($val)) {
                $this->{$key} = unserialize(serialize($val));
            }
        }
    }
}
