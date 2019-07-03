<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

final class SalvageCalculatorPlugin implements CalculatorPluginInterface
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

        $this->debug('Salvage Calculator Plugin');

        foreach ($afterBattle->getDefendingFleets() as $fleet) {
            $salvageRatio = $settings->getDefenderSalvageRatio();
            if ($fleet->isTarget()) {
                $salvageRatio = $settings->getTargetSalvageRatio();
            }

            $this->calculateSalvagedResources($fleet, $salvageRatio, $settings);
            $this->debug();
        }

        $this->debug();

        return $calculatorResponse;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param float $salvageRatio
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return void
     */
    private function calculateSalvagedResources(FleetInterface $fleet, float $salvageRatio, SettingsInterface $settings): void
    {
        $unitsLostMetal = 0.0;
        $unitsLostCrystal = 0.0;
        foreach ($fleet->getUnitsLost() as $unitId => $quantity) {
            $unit = $settings->getUnitById($unitId);
            $unitsLostMetal += $unit->getMetalCost() * $quantity;
            $unitsLostCrystal += $unit->getCrystalCost() * $quantity;
        }

        $unitsDestroyedMetal = 0.0;
        $unitsDestroyedCrystal = 0.0;
        foreach ($fleet->getUnitsDestroyed() as $unitId => $quantity) {
            $unit = $settings->getUnitById($unitId);
            $unitsDestroyedMetal += $unit->getMetalCost() * $quantity;;
            $unitsDestroyedCrystal += $unit->getCrystalCost() * $quantity;;
        }

        $salvagedMetal = ($unitsLostMetal + $unitsDestroyedMetal) * $salvageRatio;
        $salvagedCrystal = ($unitsLostCrystal + $unitsDestroyedCrystal) * $salvageRatio;

        $fleet->setSalvagedMetal($salvagedMetal);
        $fleet->setSalvagedCrystal($salvagedCrystal);

        $this->debug(sprintf('%s: Calculating salvages with ratio %s',
            $fleet->getFleetId(),
            $salvageRatio
        ));

        $this->debug(sprintf('%s: unitsLostMetal:  %s, unitsLostCrystal: %s, unitsDestroyedMetal %s, unitsDestroyedCrystal %s',
            $fleet->getFleetId(),
            $unitsLostMetal,
            $unitsLostCrystal,
            $unitsDestroyedMetal,
            $unitsDestroyedCrystal
        ));

        $this->debug(sprintf('%s: [(unitsLostMetal + unitsDestroyedMetal) * salvageRatio = salvagedMetal]',
            $fleet->getFleetId()
        ));

        $this->debug(sprintf('%s: [(%s + %s) * %s = %s]',
            $fleet->getFleetId(),
            $unitsLostMetal,
            $unitsDestroyedMetal,
            $salvageRatio,
            $salvagedMetal
        ));

        $this->debug(sprintf('%s: [(unitsLostCrystal + unitsDestroyedCrystal) * salvageRatio = salvagedCrystal]',
            $fleet->getFleetId()
        ));

        $this->debug(sprintf('%s: [(%s + %s) * %s = %s]',
            $fleet->getFleetId(),
            $unitsLostCrystal,
            $unitsDestroyedCrystal,
            $salvageRatio,
            $salvagedCrystal
        ));
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
