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
     * @var \GC\Combat\Calculator\CalculatorResponseInterface
     */
    private $calculatorResponse;

    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResponse
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface
     */
    public function calculate(CalculatorResponseInterface $calculatorResponse): CalculatorResponseInterface
    {
        $this->calculatorResponse = $calculatorResponse;

        $afterBattle = $calculatorResponse->getAfterBattle();
        $settings = $calculatorResponse->getSettings();

        $this->debug('Carrier Calculator Plugin');

        foreach (array_merge($afterBattle->getAttackingFleets(), $afterBattle->getDefendingFleets()) as $fleet) {
            /** @var $fleet \GC\Combat\Model\FleetInterface */
            $this->calculateCarrierCapacitiesFor($fleet, $settings);

            if (!$settings->isLastTick()) {
                $this->debug(sprintf('%s: Skipping carrier losses calculation (It´s not the last tick.).', $fleet->getFleetId()));
                $this->debug();
                continue;
            }

            if ($fleet->isTarget()) {
                $this->debug(sprintf('%s: Skipping carrier losses calculation (It´s a fleet of the target).', $fleet->getFleetId()));
                $this->debug();
                continue;
            }

            $this->debug(
                sprintf('%s: Is losses calculation needed? [%s < %s].',
                    $fleet->getFleetId(),
                    $fleet->getCarrierCapacityConsumed(),
                    $fleet->getCarrierCapacity()
                )
            );

            if ($fleet->getCarrierCapacityConsumed() <= $fleet->getCarrierCapacity()) {
                $this->debug(sprintf('%s: Skipping carrier losses calculation - Capacity is sufficient.', $fleet->getFleetId()));
                $this->debug();
                continue;
            }

            $this->debug(sprintf('%s: Calculating carrier capacity losses.', $fleet->getFleetId()));

            $this->calculateCarrierLossesFor($fleet, $settings);
            $this->debug();
        }

        $this->debug();

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
        $carrierCapacityConsumed = 0.0;
        $carrierCapacity = 0.0;

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

        $this->debug(sprintf('%s: Carrier Capacity - %s', $fleet->getFleetId(), $carrierCapacity));
        $this->debug(sprintf('%s: Carrier Capacity Consumed - %s', $fleet->getFleetId(), $carrierCapacityConsumed));

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
        foreach ($fleet->getUnits() as $unitId => $quantity) {
            if ($quantity === 0.0) {
                continue;
            }

            $unit = $settings->getUnitById($unitId);
            $carrierCapacityNeededForUnitType = $unit->getCarrierCapacityConsumed();

            if ($carrierCapacityNeededForUnitType === 0) {
                continue;
            }

            $this->debug(sprintf('%s: %s - carrierCapacityNeededPerUnit: %s, quantityInFleet: %s (Calculating losses in relation to quantityInFleet)',
                $fleet->getFleetId(),
                $settings->getUnitById($unitId)->getName(),
                $carrierCapacityNeededForUnitType,
                $quantity
            ));

            $quantityToLose = $quantity - (($fleet->getCarrierCapacity() / $fleet->getCarrierCapacityConsumed()) * ($carrierCapacityNeededForUnitType * $quantity));

            $quantityToLoseRounded = (int) round($quantityToLose);

            $this->debug(sprintf('%s: %s - [quantity - ((carrierCapacity / carrierCapacityConsumed) * (carrierCapacityNeededPerUnit * quantity)) = quantityToLose (rounded)]',
                $fleet->getFleetId(),
                $unit->getName()
            ));

            $this->debug(sprintf('%s: %s - [%s - ((%s / %s) * (%s * %s)) = %s (%s)]',
                $fleet->getFleetId(),
                $unit->getName(),
                $quantity,
                $fleet->getCarrierCapacity(),
                $fleet->getCarrierCapacityConsumed(),
                $carrierCapacityNeededForUnitType,
                $quantity,
                $quantityToLose,
                $quantityToLoseRounded
            ));

            $this->debug(sprintf('%s: %s - Losing %s',
                $fleet->getFleetId(),
                $unit->getName(),
                $quantityToLoseRounded
            ));

            $fleet->addInsufficientCarrierCapacityLosses($unitId, $quantityToLoseRounded);
        }
    }

    /**
     * @param string $message
     *
     * @return void
     */
    private function debug(string $message = "\n"): void
    {
        $this->calculatorResponse->addMessage($message);
    }
}
