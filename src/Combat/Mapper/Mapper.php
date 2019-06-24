<?php

declare(strict_types=1);

namespace GC\Combat\Mapper;

use GC\Combat\Model\Settings;
use GC\Combat\Model\SettingsInterface;
use GC\Combat\Model\Unit;
use GC\Combat\Model\UnitCombatSetting;
use GC\Universe\Model\Universe;

final class Mapper implements MapperInterface
{
    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Combat\Model\SettingsInterface
     */
    public function mapSettings(Universe $universe): SettingsInterface
    {
        return new Settings(
            10,
            40,
            20,
            5,
            $this->mapUnits($universe->getUnits()),
            $this->mapUnitCombatSettings($universe->getUnits())
        );
    }

    /**
     * @param \GC\Unit\Model\Unit[] $universeUnits
     *
     * @return \GC\Combat\Model\UnitInterface[]
     */
    private function mapUnits(array $universeUnits): array
    {
        $combatUnits = [];
        foreach ($universeUnits as $universeUnit) {
            $combatUnits[] = new Unit(
                $universeUnit->getUnitId(),
                $universeUnit->getName(),
                $universeUnit->getMetalCost(),
                $universeUnit->getCrystalCost(),
                $universeUnit->getCarrierSpace(),
                $universeUnit->getCarrierSpaceConsumption(),
                $universeUnit->getExtractorStealAmount(),
                $universeUnit->getExtractorGuardAmount()
            );
        }

        return $combatUnits = [];
    }

    /**
     * @param \GC\Unit\Model\Unit[] $universeUnits
     *
     * @return \GC\Combat\Model\UnitCombatSettingInterface[]
     */
    private function mapUnitCombatSettings(array $universeUnits): array
    {
        $combatUnitSettings = [];
        foreach ($universeUnits as $universeUnit) {
            foreach ($universeUnit->getUnitCombatSettings() as $universeCombatSetting) {
                $combatUnitSettings[] = new UnitCombatSetting(
                    $universeCombatSetting->getSourceUnit()->getUnitId(),
                    $universeCombatSetting->getTargetUnit()->getUnitId(),
                    $universeCombatSetting->getDistribution(),
                    (float) $universeCombatSetting->getAttackPower()
                );
            }
        }

        return $combatUnitSettings = [];
    }
}
