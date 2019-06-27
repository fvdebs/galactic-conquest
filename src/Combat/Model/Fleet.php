<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use RuntimeException;

use function array_key_exists;
use function count;
use function is_object;
use function is_array;
use function unserialize;
use function serialize;
use function round;

final class Fleet implements FleetInterface
{
    /**
     * @var int
     */
    private $fleetReference;

    /**
     * @var float[]
     */
    private $units;

    /**
     * @var string[]
     */
    private $data;

    /**
     * @var float[]
     */
    private $unitsLost = [];

    /**
     * @var float[]
     */
    private $unitsDestroyed = [];

    /**
     * @var int
     */
    private $extractorStolenMetal = 0;

    /**
     * @var int
     */
    private $extractorStolenCrystal = 0;

    /**
     * @var int
     */
    private $salvageMetal = 0;

    /**
     * @var int
     */
    private $salvageCrystal = 0;

    /**
     * @var int
     */
    private $extractorsGuarded = 0;

    /**
     * @var int
     */
    private $extractorsStealCapacity = 0;

    /**
     * @var int[]
     */
    private $insufficientCarrierLosses = [];

    /**
     * @var int
     */
    private $carrierSpace = 0;

    /**
     * @var int
     */
    private $carrierConsumption = 0;

    /**
     * @param int $fleetReference
     * @param float[] $units - unitId => quantity
     * @param string[] $data
     */
    public function __construct(int $fleetReference, array $units = [], array $data = [])
    {
        $this->fleetReference = $fleetReference;
        $this->units = $units;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getFleetReference(): int
    {
        return $this->fleetReference;
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
            $this->unitsLost[$unitId] = 0;
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
            throw new RuntimeException('can not decrease unit with given id: ' . $unitId);
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
        $quantity = 0;
        if ($this->hasUnit($unitId)) {
            $quantity = $this->units[$unitId];
        }

        return $quantity;
    }

    /**
     * @return bool
     */
    public function hasUnitsDestroyed(): bool
    {
        return count($this->unitsDestroyed) !== 0;
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
            $this->unitsDestroyed[$unitId] = 0;
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
     * @return int
     */
    public function getSalvageMetal(): int
    {
        return $this->salvageMetal;
    }

    /**
     * @param int $salvageMetal
     *
     * @return void
     */
    public function setSalvageMetal(int $salvageMetal): void
    {
        $this->salvageMetal = $salvageMetal;
    }

    /**
     * @return int
     */
    public function getSalvageCrystal(): int
    {
        return $this->salvageCrystal;
    }

    /**
     * @param int $salvageCrystal
     *
     * @return void
     */
    public function setSalvageCrystal(int $salvageCrystal): void
    {
        $this->salvageCrystal = $salvageCrystal;
    }

    /**
     * @return int
     */
    public function getExtractorStolenMetal(): int
    {
        return $this->extractorStolenMetal;
    }

    /**
     * @param int $extractorStolenMetal
     *
     * @return void
     */
    public function setExtractorStolenMetal(int $extractorStolenMetal): void
    {
        $this->extractorStolenMetal = $extractorStolenMetal;
    }

    /**
     * @return int
     */
    public function getExtractorStolenCrystal(): int
    {
        return $this->extractorStolenCrystal;
    }

    /**
     * @param int $extractorStolenCrystal
     *
     * @return void
     */
    public function setExtractorStolenCrystal(int $extractorStolenCrystal): void
    {
        $this->extractorStolenCrystal = $extractorStolenCrystal;
    }

    /**
     * @return int
     */
    public function getExtractorsGuarded(): int
    {
        return $this->extractorsGuarded;
    }

    /**
     * @param int $extractorsGuarded
     *
     * @return void
     */
    public function setExtractorsGuarded(int $extractorsGuarded): void
    {
        $this->extractorsGuarded = $extractorsGuarded;
    }

    /**
     * @return int
     */
    public function getExtractorsStealCapacity(): int
    {
        return $this->extractorsStealCapacity;
    }

    /**
     * @param int $extractorsStealCapacity
     */
    public function setExtractorsStealCapacity(int $extractorsStealCapacity): void
    {
        $this->extractorsStealCapacity = $extractorsStealCapacity;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function decreaseExtractorsStealCapacity(int $number): void
    {
        $this->extractorsStealCapacity -= $number;
    }

    /**
     * @return int[]
     */
    public function getInsufficientCarrierLosses(): array
    {
        return $this->insufficientCarrierLosses;
    }

    /**
     * @param int $unitId
     * @param int $quantity
     *
     * @return void
     */
    public function addInsufficientCarrierLosses(int $unitId, int $quantity): void
    {
        $this->decreaseUnitQuantity($unitId, $quantity);

        if (!array_key_exists($unitId, $this->insufficientCarrierLosses)) {
            $this->insufficientCarrierLosses[$unitId] = 0;
        }

        $this->insufficientCarrierLosses[$unitId] += $quantity;
    }

    /**
     * @return int
     */
    public function getCarrierSpace(): int
    {
        return $this->carrierSpace;
    }

    /**
     * @param int $carrierSpace
     *
     * @return void
     */
    public function setCarrierSpace(int $carrierSpace): void
    {
        $this->carrierSpace = $carrierSpace;
    }

    /**
     * @return int
     */
    public function getCarrierConsumption(): int
    {
        return $this->carrierConsumption;
    }

    /**
     * @param int $carrierConsumption
     *
     * @return void
     */
    public function setCarrierConsumption(int $carrierConsumption): void
    {
        $this->carrierConsumption = $carrierConsumption;
    }

    /**
     * @return void
     */
    public function normalize(): void
    {
        $this->units = $this->arrayToInt($this->units);
        $this->unitsLost = $this->arrayToInt($this->units);
        $this->unitsDestroyed = $this->arrayToInt($this->units);
    }

    /**
     * @param float[] $array
     *
     * @return int[]
     */
    private function arrayToInt(array $array): array
    {
        $normalized = [];

        foreach ($array as $index => $value) {
            $normalized[$index] = (int) round($value);
        }

        return $normalized;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        foreach($this as $key => $val) {
            if (is_object($val) || (is_array($val))) {
                $this->{$key} = unserialize(serialize($val));
            }
        }
    }
}
