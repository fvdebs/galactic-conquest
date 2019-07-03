<?php

declare(strict_types=1);

namespace GC\Combat\Calculator\Plugin;

use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

use function floor;

final class ExtractorCalculatorPlugin implements CalculatorPluginInterface
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

        $this->debug('Extractor Calculator Plugin');

        $extractorsProtectedTotal = $this->calculateNumberOfProtectedExtractors(
            $afterBattle->getDefendingFleets(),
            $settings
        );

        $extractorStealCapacityTotal = $this->calculateNumberOfStealCapacity(
            $afterBattle->getAttackingFleets(),
            $settings
        );

        $this->debug(sprintf('defenderProtectionCapacity: %s, attackerStealCapacity: %s, extractorStealRatio: %s',
            $extractorsProtectedTotal,
            $extractorStealCapacityTotal,
            $settings->getExtractorStealRatio()
        ));

        if ($extractorsProtectedTotal >= $extractorStealCapacityTotal) {
            $this->debug(sprintf('Skipping extractor steal calculation. Insufficient steal capacity.'));
            return $calculatorResponse;
        }

        $totalMetalExtractorsToSteal = $afterBattle->getTargetExtractorsMetal() *  $settings->getExtractorStealRatio();
        $totalCrystalExtractorsToSteal = $afterBattle->getTargetExtractorsCrystal() *  $settings->getExtractorStealRatio();

        $this->debug(sprintf('%s targetMetalExtractors, %s targetCrystalExtractors, %s totalMetalExtractorsToSteal, %s totalCrystalExtractorsToSteal',
            $afterBattle->getTargetExtractorsMetal(),
            $afterBattle->getTargetExtractorsCrystal(),
            $totalMetalExtractorsToSteal,
            $totalCrystalExtractorsToSteal
        ));

        foreach ($afterBattle->getAttackingFleets() as $fleet) {
            if ($fleet->getExtractorsStealCapacity() === 0.0) {
                continue;
            }

            $this->debug();

            $this->calculateExtractorsStolenFor(
                $afterBattle,
                $fleet,
                $extractorStealCapacityTotal,
                $extractorsProtectedTotal,
                $totalMetalExtractorsToSteal,
                $totalCrystalExtractorsToSteal
            );
        }

        $this->debug();

        return $calculatorResponse;
    }

    /**
     * @param \GC\Combat\Model\BattleInterface $afterBattle
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param float $extractorStealCapacityTotal
     * @param float $extractorsProtectedTotal
     * @param float $totalMetalExtractorsToSteal
     * @param float $totalCrystalExtractorsToSteal
     *
     * @return void
     */
    private function calculateExtractorsStolenFor(
        BattleInterface $afterBattle,
        FleetInterface $fleet,
        float $extractorStealCapacityTotal,
        float $extractorsProtectedTotal,
        float $totalMetalExtractorsToSteal,
        float $totalCrystalExtractorsToSteal
    ): void {
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

        $this->debug(sprintf('%s: %s fleetStealCapacity',
            $fleet->getFleetId(),
            $fleet->getExtractorsStealCapacity()
        ));

        $this->debug(sprintf('%s: [totalMetalExtractorsToSteal / extractorStealCapacityTotal) * fleetStealCapacity = extractorsStolenMetal (extractorsStolenMetalFloored)]',
            $fleet->getFleetId()
        ));

        $this->debug(sprintf('%s: [%s / %s) * %s = %s (%s)]',
            $fleet->getFleetId(),
            $totalMetalExtractorsToSteal,
            $extractorStealCapacityTotal,
            $fleet->getExtractorsStealCapacity(),
            $extractorsStolenMetal,
            $extractorsStolenMetalFloored
        ));

        $this->debug(sprintf('%s: [totalCrystalExtractorsToSteal / extractorStealCapacityTotal) * fleetStealCapacity = extractorsStoleCrystal (extractorsStolenCrystalFloored)]',
            $fleet->getFleetId()
        ));

        $this->debug(sprintf('%s: [%s / %s) * %s = %s (%s)]',
            $fleet->getFleetId(),
            $extractorsStolenCrystal,
            $extractorStealCapacityTotal,
            $fleet->getExtractorsStealCapacity(),
            $extractorsStolenCrystal,
            $extractorsStolenCrystalFloored
        ));

        $this->debug(sprintf('%s: %s stolenMetalExtractors, %s stolenCrystalExtractors',
            $fleet->getFleetId(),
            $extractorsStolenMetalFloored,
            $extractorsStolenCrystalFloored
        ));
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return float
     */
    private function calculateNumberOfProtectedExtractors(array $fleets, SettingsInterface $settings): float
    {
        $extractorsProtectedTotal = 0.0;

        foreach ($fleets as $fleet) {

            $currentFleetCanProtect = 0.0;

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
        $extractorsStealCapacityTotal = 0.0;

        foreach ($fleets as $fleet) {

            $currentFleetCanSteal = 0.0;

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
