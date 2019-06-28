<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface SettingsInterface
{
    /**
     * @return float
     */
    public function getExtractorStealRatio(): float;

    /**
     * @return float
     */
    public function getTargetSalvageRatio(): float;

    /**
     * @return float
     */
    public function getDefenderSalvageRatio(): float;

    /**
     * @return int
     */
    public function getCombatTicks(): int;

    /**
     * @return \GC\Combat\Model\UnitInterface[]
     */
    public function getUnits(): array;

    /**
     * @return bool
     */
    public function isLastTick(): bool;

    /**
     * @return bool
     */
    public function isPreFireTick(): bool;

    /**
     * @param int $unitId
     *
     * @return \GC\Combat\Model\UnitInterface
     */
    public function getUnitById(int $unitId): UnitInterface;

    /**
     * @return \GC\Combat\Model\UnitCombatSettingInterface[]
     */
    public function getUnitCombatSettings(): array;

    /**
     * @param int $unitId
     *
     * @return \GC\Combat\Model\UnitCombatSettingInterface[]
     */
    public function getUnitCombatSettingTargetsOf(int $unitId): array;
}
