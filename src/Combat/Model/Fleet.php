<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use function array_key_exists;

final class Fleet implements FleetInterface
{
    /**
     * @var int[]
     */
    private $units = [];

    /**
     * @var string[]
     */
    private $data = [];

    /**
     * @var int[]
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
     * @param int[] $units
     * @param string[] $data
     */
    public function __construct(array $units = [], array $data = [])
    {
        $this->units = $units;
        $this->data = $data;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
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
     * @param int $quantity
     *
     * @return void
     */
    public function decreaseUnitQuantity(int $unitId, int $quantity): void
    {
        if (!$this->hasUnit($unitId)) {
            return;
        }

        $this->units[$unitId] -= $quantity;
    }

    /**
     * @param int $unitId
     *
     * @return int
     */
    public function getQuantityOf(int $unitId): int
    {
        $quantity = 0;
        if ($this->hasUnit($unitId)) {
            $quantity = $this->units[$unitId];
        }

        return $quantity;
    }

    /**
     * @param int $unitId
     * @param int $quantity
     *
     * @return void
     */
    public function increaseUnitDestroyedQuantity(int $unitId, int $quantity): void
    {
        if (!$this->hasUnitDestroyed($unitId)) {
            $this->unitsDestroyed[$unitId] = 0;
        }

        $this->units[$unitId] += $quantity;
    }

    /**
     * @param int $unitId
     *
     * @return bool
     */
    private function hasUnitDestroyed(int $unitId): bool
    {
        return array_key_exists($unitId, $this->unitsDestroyed);
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
    public function increaseSalvageMetal(int $salvageMetal): void
    {
        $this->salvageMetal += $salvageMetal;
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
    public function increaseSalvageCrystal(int $salvageCrystal): void
    {
        $this->salvageCrystal += $salvageCrystal;
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
}
