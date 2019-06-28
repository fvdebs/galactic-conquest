<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface UnitCombatSettingInterface
{
    /**
     * @return int
     */
    public function getSourceUnitId(): int;

    /**
     * @return int
     */
    public function getTargetUnitId(): int;

    /**
     * @return float
     */
    public function getDistributionRatio(): float;

    /**
     * @return float
     */
    public function getAttackPower(): float;
}
