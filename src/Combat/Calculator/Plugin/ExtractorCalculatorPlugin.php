<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Model\SettingsInterface;

use function floor;

final class ExtractorCalculatorPlugin implements CalculatorPluginInterface
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

        $extractorsProtectedTotal = $this->calculateNumberOfProtectedExtractors(
            $afterBattle->getDefendingFleets(),
            $settings
        );

        $extractorStealCapacityTotal = $this->calculateNumberOfStealCapacity(
            $afterBattle->getAttackingFleets(),
            $settings
        );

        if ($extractorStealCapacityTotal === 0.0 || $extractorsProtectedTotal >= $extractorStealCapacityTotal) {
            return $calculatorResponse;
        }

        $totalMetalExtractorsToSteal = $afterBattle->getTargetExtractorsMetal() *  $settings->getExtractorStealRatio();
        $totalCrystalExtractorsToSteal = $afterBattle->getTargetExtractorsCrystal() *  $settings->getExtractorStealRatio();

        foreach ($afterBattle->getAttackingFleets() as $fleet) {
            if ($fleet->getExtractorsStealCapacity() === 0.0) {
                continue;
            }

            $extractorsStolenMetal =
                ($totalMetalExtractorsToSteal / $extractorStealCapacityTotal) * $fleet->getExtractorsStealCapacity();

            $extractorsStolenCrystal =
                ($totalCrystalExtractorsToSteal / $extractorStealCapacityTotal) * $fleet->getExtractorsStealCapacity();

            $extractorsStolenMetalFloored = (int) floor($extractorsStolenMetal);
            $extractorsStolenCrystalFloored = (int) floor($extractorsStolenCrystal);

            $fleet->setExtractorStolenMetal($extractorsStolenMetalFloored);
            $fleet->setExtractorStolenCrystal($extractorsStolenCrystalFloored);

            $afterBattle->decreaseTargetExtractorsMetal($extractorsStolenMetalFloored);
            $afterBattle->decreaseTargetExtractorsCrystal($extractorsStolenCrystalFloored);
        }

        return $calculatorResponse;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return float
     */
    private function calculateNumberOfProtectedExtractors(array $fleets, SettingsInterface $settings): float
    {
        $extractorsProtectedTotal = 0;

        foreach ($fleets as $fleet) {

            $currentFleetCanProtect = 0;

            foreach ($fleet->getUnits() as $unitId => $quantity) {
                $currentUnitTypeCanProtect = $settings->getUnitById($unitId)
                    ->getExtractorProtectAmount();

                $currentUnitQuantityCanProtect = $currentUnitTypeCanProtect * $quantity;

                $currentFleetCanProtect += $currentUnitQuantityCanProtect;
            }

            $extractorsProtectedTotal += $currentFleetCanProtect;

            $fleet->setExtractorsProtected($currentFleetCanProtect);
        }

        return $extractorsProtectedTotal;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return float
     */
    private function calculateNumberOfStealCapacity(array $fleets, SettingsInterface $settings): float
    {
        $extractorsStealCapacityTotal = 0;

        foreach ($fleets as $fleet) {

            $currentFleetCanSteal = 0;

            foreach ($fleet->getUnits() as $unitId => $quantity) {
                $currentUnitTypeCanSteal = $settings->getUnitById($unitId)
                    ->getExtractorStealAmount();

                $currentUnitQuantityCanSteal = $currentUnitTypeCanSteal * $quantity;

                $currentFleetCanSteal += $currentUnitQuantityCanSteal;
            }

            $extractorsStealCapacityTotal += $currentFleetCanSteal;

            $fleet->setExtractorsStealCapacity($currentFleetCanSteal);
        }

        return $extractorsStealCapacityTotal;
    }
}
