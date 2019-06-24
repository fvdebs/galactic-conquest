<?php

declare(strict_types=1);

namespace GC\Combat\Model;

final class Settings implements SettingsInterface
{
    /**
     * @var int
     */
    private $extractorStealPerCentModifier;

    /**
     * @var int
     */
    private $targetSalvagePerCentModifier;

    /**
     * @var int
     */
    private $defenderSalvagePerCentModifier;

    /**
     * @var int
     */
    private $combatTicks;

    /**
     * @var \GC\Combat\Model\UnitInterface[]
     */
    private $units;

    /**
     * @var \GC\Combat\Model\UnitCombatSetting[]
     */
    private $unitCombatSettings;

    /**
     * @param int $extractorStealPerCentModifier
     * @param int $targetSalvagePerCentModifier
     * @param int $defenderSalvagePerCentModifier
     * @param int $combatTicks
     * @param \GC\Combat\Model\UnitInterface[] $units
     * @param \GC\Combat\Model\UnitCombatSetting[] $unitCombatSettings
     */
    public function __construct(
        int $extractorStealPerCentModifier,
        int $targetSalvagePerCentModifier,
        int $defenderSalvagePerCentModifier,
        int $combatTicks,
        array $units,
        array $unitCombatSettings
    ) {
        $this->extractorStealPerCentModifier = $extractorStealPerCentModifier;
        $this->targetSalvagePerCentModifier = $targetSalvagePerCentModifier;
        $this->defenderSalvagePerCentModifier = $defenderSalvagePerCentModifier;
        $this->combatTicks = $combatTicks;
        $this->units = $units;
        $this->unitCombatSettings = $unitCombatSettings;
    }

    /**
     * @return int
     */
    public function getExtractorStealPerCentModifier(): int
    {
        return $this->extractorStealPerCentModifier;
    }

    /**
     * @return int
     */
    public function getTargetSalvagePerCentModifier(): int
    {
        return $this->targetSalvagePerCentModifier;
    }

    /**
     * @return int
     */
    public function getDefenderSalvagePerCentModifier(): int
    {
        return $this->defenderSalvagePerCentModifier;
    }

    /**
     * @return int
     */
    public function getCombatTicks(): int
    {
        return $this->combatTicks;
    }

    /**
     * @return \GC\Combat\Model\UnitInterface[]
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * @param int $unitId
     *
     * @return \GC\Combat\Model\UnitInterface
     */
    public function getUnitById(int $unitId): UnitInterface
    {
        foreach ($this->getUnits() as $unit) {
            if ($unit->getUnitId() === $unitId) {
                return $unit;
            }
        }

        throw new \Exception('unit not given: ' . $unitId);
    }

    /**
     * @return \GC\Combat\Model\UnitCombatSettingInterface[]
     */
    public function getUnitCombatSettings(): array
    {
        return $this->unitCombatSettings;
    }

    /**
     * @param int $unitId
     *
     * @return \GC\Combat\Model\UnitCombatSettingInterface[]
     */
    public function getUnitCombatSettingsTargetsOf(int $unitId): array
    {
        $unitCombatSettings = [];
        foreach ($this->getUnitCombatSettings() as $unitCombatSetting) {
            if ($unitCombatSetting->getSourceUnitId() === $unitId) {
                $unitCombatSettings[] = $unitCombatSetting;
            }
        }

        return $unitCombatSettings;
    }
}
