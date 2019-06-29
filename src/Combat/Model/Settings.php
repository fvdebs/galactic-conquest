<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use GC\Combat\Exception\UnitNotFoundException;

final class Settings implements SettingsInterface
{
    /**
     * @var float
     */
    private $extractorStealRatio;

    /**
     * @var float
     */
    private $targetSalvageRatio;

    /**
     * @var float
     */
    private $defenderSalvageRatio;

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
     * @var bool
     */
    private $isLastTick;
    /**
     * @var bool
     */
    private $isPreFireTick;

    /**
     * @param float $extractorStealRatio
     * @param float $targetSalvageRatio
     * @param float $defenderSalvageRatio
     * @param int $combatTicks
     * @param \GC\Combat\Model\UnitInterface[] $units
     * @param \GC\Combat\Model\UnitCombatSetting[] $unitCombatSettings
     * @param bool $isLastTick
     * @param bool $isPreFireTick - no functionality
     */
    public function __construct(
        float $extractorStealRatio,
        float $targetSalvageRatio,
        float $defenderSalvageRatio,
        int $combatTicks,
        array $units,
        array $unitCombatSettings,
        bool $isLastTick = false,
        bool $isPreFireTick = false
    ) {
        $this->extractorStealRatio = $extractorStealRatio;
        $this->targetSalvageRatio = $targetSalvageRatio;
        $this->defenderSalvageRatio = $defenderSalvageRatio;
        $this->combatTicks = $combatTicks;
        $this->units = $units;
        $this->unitCombatSettings = $unitCombatSettings;
        $this->isLastTick = $isLastTick;
        $this->isPreFireTick = $isPreFireTick;
    }

    /**
     * @return float
     */
    public function getExtractorStealRatio(): float
    {
        return $this->extractorStealRatio;
    }

    /**
     * @return float
     */
    public function getTargetSalvageRatio(): float
    {
        return $this->targetSalvageRatio;
    }

    /**
     * @return float
     */
    public function getDefenderSalvageRatio(): float
    {
        return $this->defenderSalvageRatio;
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
     * @return bool
     */
    public function isLastTick(): bool
    {
        return $this->isLastTick;
    }

    /**
     * @return bool
     */
    public function isPreFireTick(): bool
    {
        return $this->isPreFireTick;
    }

    /**
     * @param bool $isLastTick
     *
     * @return void
     */
    public function setIsLastTick(bool $isLastTick): void
    {
        $this->isLastTick = $isLastTick;
    }

    /**
     * @param bool $isPreFireTick
     *
     * @return void
     */
    public function setIsPreFireTick(bool $isPreFireTick): void
    {
        $this->isPreFireTick = $isPreFireTick;
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

        throw UnitNotFoundException::fromUnitId($unitId);
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
    public function getUnitCombatSettingTargetsOf(int $unitId): array
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
