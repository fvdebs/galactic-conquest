<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface UnitInterface
{
    /**
     * @return int
     */
    public function getUnitId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getMetalCost(): int;

    /**
     * @return int
     */
    public function getCrystalCost(): int;

    /**
     * @return int
     */
    public function getCarrierCapacity(): int;

    /**
     * @return int
     */
    public function getCarrierCapacityConsumed(): int;

    /**
     * @return int
     */
    public function getExtractorStealAmount(): int;

    /**
     * @return int
     */
    public function getExtractorProtectAmount(): int;

    /**
     * @return string
     */
    public function getGroup(): string;
}
