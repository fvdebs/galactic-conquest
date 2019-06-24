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
    private $carrierSpace;

    /**
     * @var int
     */
    private $carrierSpaceConsumption;

    /**
     * @var int
     */
    private $extractorStealAmount;

    /**
     * @var int
     */
    private $extractorGuardAmount;

    /**
     * @param int $unitId
     * @param string $name
     * @param int $metalCost
     * @param int $crystalCost
     * @param int $carrierSpace
     * @param int $carrierSpaceConsumption
     * @param int $extractorStealAmount
     * @param int $extractorGuardAmount
     */
    public function __construct(
        int $unitId,
        string $name,
        int $metalCost,
        int $crystalCost,
        int $carrierSpace,
        int $carrierSpaceConsumption,
        int $extractorStealAmount,
        int $extractorGuardAmount
    ) {
        $this->unitId = $unitId;
        $this->name = $name;
        $this->metalCost = $metalCost;
        $this->crystalCost = $crystalCost;
        $this->carrierSpace = $carrierSpace;
        $this->carrierSpaceConsumption = $carrierSpaceConsumption;
        $this->extractorStealAmount = $extractorStealAmount;
        $this->extractorGuardAmount = $extractorGuardAmount;
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
    public function getCarrierSpace(): int
    {
        return $this->carrierSpace;
    }

    /**
     * @return int
     */
    public function getCarrierSpaceConsumption(): int
    {
        return $this->carrierSpaceConsumption;
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
    public function getExtractorGuardAmount(): int
    {
        return $this->extractorGuardAmount;
    }
}
