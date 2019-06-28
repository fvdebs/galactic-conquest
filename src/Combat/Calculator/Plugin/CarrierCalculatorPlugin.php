<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

use function array_merge;

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
            $this->calculateCarrierCapacitiesFor($fleet, $settings);

            if ($after->isFleetFromTarget($fleet)) {
                continue;
            }

            if (!$settings->isLastTick()) {
               continue;
            }

            $this->calculateCarrierLossesFor($fleet, $settings);
        }

        return $after;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return void
     */
    public function calculateCarrierCapacitiesFor(FleetInterface $fleet, SettingsInterface $settings): void
    {
        $carrierCapacityConsumed = 0;
        $carrierCapacity = 0;

        foreach ($fleet->getUnits() as $unitId => $quantity) {
            if ($quantity === 0.0) {
                continue;
            }

            $unit = $settings->getUnitById($unitId);

            if ($unit->getCarrierCapacityConsumed() > 0) {
                $carrierCapacityConsumed += $unit->getCarrierCapacityConsumed() * $quantity;
            }

            if ($unit->getCarrierCapacity() > 0) {
                $carrierCapacity += $unit->getCarrierCapacity() * $quantity;
            }
        }

        $fleet->setCarrierCapacityConsumed($carrierCapacityConsumed);
        $fleet->setCarrierCapacity($carrierCapacity);
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return void
     */
    public function calculateCarrierLossesFor(FleetInterface $fleet, SettingsInterface $settings): void
    {
        if ($fleet->getCarrierCapacityConsumed() <= $fleet->getCarrierCapacity()) {
           return;
        }

        foreach ($fleet->getUnits() as $unitId => $quantity) {
            if ($quantity === 0.0) {
                continue;
            }

            $carrierCapacityNeededForUnitType = $settings->getUnitById($unitId)
                ->getCarrierCapacityConsumed();

            if ($carrierCapacityNeededForUnitType === 0) {
                continue;
            }

            if ($fleet->getCarrierCapacity() === 0.0) {
                $fleet->addInsufficientCarrierCapacityLosses($unitId, $quantity);
                continue;
            }

            $quantityLeft = ($fleet->getCarrierCapacity() / $fleet->getCarrierCapacityConsumed()) * ($carrierCapacityNeededForUnitType * $quantity);

            $quantityToLose = $quantity - $quantityLeft;


            $fleet->addInsufficientCarrierCapacityLosses($unitId, (int) round($quantityToLose));
        }
    }
}
