<?php

declare(strict_types=1);

namespace GC\Combat\Model;

final class Unit implements UnitInterface
{
    /**
     * @var int
     */
    private $unitId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $metalCost;

    /**
     * @var int
     */
    private $crystalCost;

    /**
     * @var int
     */
    private $carrierCapacity;

    /**
     * @var int
     */
    private $carrierCapacityConsumed;

    /**
     * @var int
     */
    private $extractorStealAmount;

    /**
     * @var int
     */
    private $extractorProtectAmount;

    /**
     * @var string
     */
    private $group;

    /**
     * @param int $unitId
     * @param string $name
     * @param int $metalCost
     * @param int $crystalCost
     * @param int $carrierCapacity
     * @param int $carrierCapacityConsumed
     * @param int $extractorStealAmount
     * @param int $extractorProtectAmount
     * @param string $group - default: ''
     */
    public function __construct(
        int $unitId,
        string $name,
        int $metalCost,
        int $crystalCost,
        int $carrierCapacity,
        int $carrierCapacityConsumed,
        int $extractorStealAmount,
        int $extractorProtectAmount,
        string $group = ''
    ) {
        $this->unitId = $unitId;
        $this->name = $name;
        $this->metalCost = $metalCost;
        $this->crystalCost = $crystalCost;
        $this->carrierCapacity = $carrierCapacity;
        $this->carrierCapacityConsumed = $carrierCapacityConsumed;
        $this->extractorStealAmount = $extractorStealAmount;
        $this->extractorProtectAmount = $extractorProtectAmount;
        $this->group = $group;
    }

    /**
     * @return int
     */
    public function getUnitId(): int
    {
        return $this->unitId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMetalCost(): int
    {
        return $this->metalCost;
    }

    /**
     * @return int
     */
    public function getCrystalCost(): int
    {
        return $this->crystalCost;
    }

    /**
     * @return int
     */
    public function getCarrierCapacity(): int
    {
        return $this->carrierCapacity;
    }

    /**
     * @return int
     */
    public function getCarrierCapacityConsumed(): int
    {
        return $this->carrierCapacityConsumed;
    }

    /**
     * @return int
     */
    public function getExtractorStealAmount(): int
    {
        return $this->extractorStealAmount;
    }

    /**
     * @return int
     */
    public function getExtractorProtectAmount(): int
    {
        return $this->extractorProtectAmount;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }
}
