<?php

declare(strict_types=1);

namespace GC\Combat\Model;

final class UnitCombatSetting implements UnitCombatSettingInterface
{
    /**
     * @var int
     */
    private $sourceUnitId;

    /**
     * @var int
     */
    private $targetUnitId;

    /**
     * @var float
     */
    private $distributionRatio;

    /**
     * @var float
     */
    private $attackPower;

    /**
     * @param int $sourceUnitId
     * @param int $targetUnitId
     * @param float $distributionRatio
     * @param float $attackPower
     */
    public function __construct(
        int $sourceUnitId,
        int $targetUnitId,
        float $distributionRatio,
        float $attackPower
    ) {
        $this->sourceUnitId = $sourceUnitId;
        $this->targetUnitId = $targetUnitId;
        $this->distributionRatio = $distributionRatio;
        $this->attackPower = $attackPower;
    }

    /**
     * @return int
     */
    public function getSourceUnitId(): int
    {
        return $this->sourceUnitId;
    }

    /**
     * @return int
     */
    public function getTargetUnitId(): int
    {
        return $this->targetUnitId;
    }

    /**
     * @return float
     */
    public function getDistributionRatio(): float
    {
        return $this->distributionRatio;
    }

    /**
     * @return float
     */
    public function getAttackPower(): float
    {
        return $this->attackPower;
    }
}
