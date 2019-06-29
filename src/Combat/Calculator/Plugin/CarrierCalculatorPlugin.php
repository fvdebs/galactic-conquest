<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

use function array_merge;

final class CarrierCalculatorPlugin implements CalculatorPluginInterface
{
    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResponse
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface
     */
    public function calculate(CalculatorResponseInterface $calculatorResponse): CalculatorResponseInterface
    {
        $afterBattle = $calculatorResponse->getAfterBattle();
        $settings = $calculatorResponse->getSettings();

        foreach (array_merge($afterBattle->getAttackingFleets(), $afterBattle->getDefendingFleets()) as $fleet) {
            $this->calculateCarrierCapacitiesFor($fleet, $settings);

            if ($afterBattle->isFleetFromTarget($fleet)) {
                continue;
            }

            if (!$settings->isLastTick()) {
               continue;
            }

            $this->calculateCarrierLossesFor($fleet, $settings);
        }

        return $calculatorResponse;
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
