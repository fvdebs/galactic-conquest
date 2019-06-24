<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface SettingsInterface
{
    /**
     * @return int
     */
    public function getExtractorStealPerCentModifier(): int;

    /**
     * @return int
     */
    public function getTargetSalvagePerCentModifier(): int;

    /**
     * @return int
     */
    public function getDefenderSalvagePerCentModifier(): int;

    /**
     * @return int
     */
    public function getCombatTicks(): int;

    /**
     * @return \GC\Combat\Model\UnitInterface[]
     */
    public function getUnits(): array;

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
    public function getUnitCombatSettingsTargetsOf(int $unitId): array;
}
