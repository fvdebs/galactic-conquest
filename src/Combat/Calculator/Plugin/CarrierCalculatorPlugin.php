<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

use function array_merge;
use function round;

final class CarrierCalculatorPlugin implements CalculatorPluginInterface
{
    private const KEY_PLAYER_ID = 'playerId';

    /**
     * @param \GC\Combat\Model\BattleInterface $before
     * @param \GC\Combat\Model\BattleInterface $after
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Model\BattleInterface - $after
     */
    public function calculate(BattleInterface $before, BattleInterface $after, SettingsInterface $settings): BattleInterface
    {
        foreach (array_merge($after->getAttackingFleets(), $after->getDefendingFleets()) as $fleet) {
            $this->calculateCarrierConsumptionAndSpaceFor($fleet, $settings);

            if (! $after->compareFleetDataValueWithTargetDataValue($fleet, static::KEY_PLAYER_ID)) {
                // is target fleet
                $this->calculateCarrierLossesFor($fleet, $settings);
            }
        }

        return $after;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return void
     */
    public function calculateCarrierConsumptionAndSpaceFor(FleetInterface $fleet, SettingsInterface $settings): void
    {
        $carrierSpaceConsumption = 0;
        $carrierSpace = 0;

        foreach ($fleet->getUnits() as $unitId => $quantity) {
            $unit = $settings->getUnitById($unitId);

            if ($unit->getCarrierSpaceConsumption() > 0) {
                $carrierSpaceConsumption += $unit->getCarrierSpaceConsumption() * $quantity;
            }

            if ($unit->getCarrierSpace() > 0) {
                $carrierSpace += $unit->getCarrierSpace() * $quantity;
            }
        }

        $fleet->setCarrierConsumption($carrierSpaceConsumption);
        $fleet->setCarrierSpace($carrierSpace);
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return void
     */
    public function calculateCarrierLossesFor(FleetInterface $fleet, SettingsInterface $settings): void
    {
        if ($fleet->getCarrierConsumption() <= $fleet->getCarrierSpace()) {
           return;
        }

        foreach ($fleet->getUnits() as $unitId => $quantity) {
            if ($quantity === 0) {
                continue;
            }

            $carrierCapacityNeededForUnitType = $settings->getUnitById($unitId)
                ->getCarrierSpaceConsumption();

            if ($carrierCapacityNeededForUnitType === 0) {
                continue;
            }

            if ($fleet->getCarrierSpace() === 0) {
                $fleet->addInsufficientCarrierLosses($unitId, $quantity);
                continue;
            }

            $quantityToLose = ($fleet->getCarrierSpace() / $fleet->getCarrierConsumption()) * ($carrierCapacityNeededForUnitType * $quantity);

            $fleet->addInsufficientCarrierLosses($unitId, (int) round($quantityToLose));
        }
    }
}
