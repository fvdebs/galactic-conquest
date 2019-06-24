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
     * @var int
     */
    private $distributionPerCent;

    /**
     * @var float
     */
    private $attackPower;

    /**
     * @param int $sourceUnitId
     * @param int $targetUnitId
     * @param int $distributionPerCent
     * @param float $attackPower
     */
    public function __construct(
        int $sourceUnitId,
        int $targetUnitId,
        int $distributionPerCent,
        float $attackPower
    ) {
        $this->sourceUnitId = $sourceUnitId;
        $this->targetUnitId = $targetUnitId;
        $this->distributionPerCent = $distributionPerCent;
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
     * @return int
     */
    public function getDistributionPerCent(): int
    {
        return $this->distributionPerCent;
    }

    /**
     * @return float
     */
    public function getAttackPower(): float
    {
        return $this->attackPower;
    }
}
