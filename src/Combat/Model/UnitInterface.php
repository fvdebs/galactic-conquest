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
    public function getCarrierSpace(): int;

    /**
     * @return int
     */
    public function getCarrierSpaceConsumption(): int;

    /**
     * @return int
     */
    public function getExtractorStealAmount(): int;

    /**
     * @return int
     */
    public function getExtractorGuardAmount(): int;
}
