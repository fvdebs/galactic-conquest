<?php

declare(strict_types=1);

namespace GC\Combat\Service;

use GC\Combat\Calculator\CalculatorInterface;
use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Format\JsonFormatterInterface;
use GC\Combat\Mapper\SettingsMapperInterface;
use GC\Combat\Model\Battle;
use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\Fleet;
use GC\Combat\Model\SettingsInterface;
use GC\Combat\Report\CombatReportGeneratorInterface;
use GC\Combat\Report\CombatReportGeneratorResponseInterface;
use GC\Universe\Model\Universe;

final class CombatService implements CombatServiceInterface
{
    /**
     * @var \GC\Combat\Calculator\CalculatorInterface
     */
    private $calculator;

    /**
     * @var \GC\Combat\Format\JsonFormatterInterface
     */
    private $jsonFormatter;

    /**
     * @var \GC\Combat\Report\CombatReportGeneratorInterface
     */
    private $combatReportGenerator;

    /**
     * @var \GC\Combat\Mapper\SettingsMapperInterface
     */
    private $settingsMapper;

    /**
     * @param \GC\Combat\Calculator\CalculatorInterface $calculator
     * @param \GC\Combat\Format\JsonFormatterInterface $jsonFormatter
     * @param \GC\Combat\Report\CombatReportGeneratorInterface $combatReportGenerator
     * @param \GC\Combat\Mapper\SettingsMapperInterface $settingsMapper
     */
    public function __construct(
        CalculatorInterface $calculator,
        JsonFormatterInterface $jsonFormatter,
        CombatReportGeneratorInterface $combatReportGenerator,
        SettingsMapperInterface $settingsMapper
    ) {
        $this->calculator = $calculator;
        $this->jsonFormatter = $jsonFormatter;
        $this->combatReportGenerator = $combatReportGenerator;
        $this->settingsMapper = $settingsMapper;
    }

    /**
     * @param \GC\Combat\Model\BattleInterface $battle
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface
     */
    public function calculate(BattleInterface $battle, SettingsInterface $settings): CalculatorResponseInterface
    {
        return $this->calculator->calculate($battle, $settings);
    }

    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     *
     * @return string
     */
    public function formatToJson(CalculatorResponseInterface $calculatorResult): string
    {
        return $this->jsonFormatter->format($calculatorResult);
    }

    /**
     * @param string $json
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string $pointOfViewDisplayNameDataKey
     *
     * @return \GC\Combat\Report\CombatReportGeneratorResponseInterface
     */
    public function generateCombatReportFromJson(
        string $json,
        $pointOfView,
        string $pointOfViewDataKey,
        string $pointOfViewDisplayNameDataKey
    ): CombatReportGeneratorResponseInterface {
        return $this->combatReportGenerator->generateCombatReportFromJson(
            $json,
            $pointOfView,
            $pointOfViewDataKey,
            $pointOfViewDisplayNameDataKey
        );
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Combat\Model\SettingsInterface
     */
    public function mapSettingsFromUniverse(Universe $universe): SettingsInterface
    {
        return $this->settingsMapper->mapFrom($universe);
    }

    /**
     * @param \GC\Combat\Service\CombatSimulationRequestInterface $simulationRequest
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface[]
     */
    public function simulate(CombatSimulationRequestInterface $simulationRequest): array
    {
        $calculatorResults = [];

        $battle = null;

        for ($tick = 1; $tick <= $simulationRequest->getTicksToSimulate(); $tick++) {
            $battle = $this->createBattleForTick($simulationRequest, $battle, $tick);

            if ($tick >= $simulationRequest->getTicksToSimulate()) {
                $simulationRequest->getSettings()->setIsLastTick(true);
            }

            $calculatorResult = $this->calculate($battle, $simulationRequest->getSettings());
            $battle = $calculatorResult->getAfterBattle();
            $calculatorResults[] = $calculatorResult;
        }

        return $calculatorResults;
    }

    /**
     * @param \GC\Combat\Service\CombatSimulationRequestInterface $simulationRequest
     * @param \GC\Combat\Model\BattleInterface|null $before
     * @param int $tick
     *
     * @return \GC\Combat\Model\BattleInterface
     */
    private function createBattleForTick(CombatSimulationRequestInterface $simulationRequest, ?BattleInterface $before = null, int $tick = 1): BattleInterface
    {
        $extractorsMetal = $simulationRequest->getTargetExtractorsMetal();
        $extractorsCrystal = $simulationRequest->getTargetExtractorsCrystal();

        if ($before !== null) {
            $extractorsMetal = $before->getTargetExtractorsMetal();
            $extractorsCrystal = $before->getTargetExtractorsCrystal();
        }

        $battle = new Battle(
            [],
            [],
            $extractorsMetal,
            $extractorsCrystal,
            $simulationRequest->getData()
        );

        foreach ($simulationRequest->getAttackingFleets() as $fleet) {
            if (! $simulationRequest->isFleetInTick($fleet->getFleetId(), $tick)) {
                continue;
            }

            if ($before !== null && $before->hasFleetById($fleet->getFleetId(), $before->getAttackingFleets())) {
                $beforeFleet = $before->getFleetById($fleet->getFleetId(), $before->getAttackingFleets());
                $battle->addAttackingFleet(
                    new Fleet($fleet->getFleetId(), $beforeFleet->getUnits(), $beforeFleet->getData())
                );
                continue;
            }

            $battle->addAttackingFleet(clone $fleet);
        }

        foreach ($simulationRequest->getDefendingFleets() as $fleet) {
            if (! $simulationRequest->isFleetInTick($fleet->getFleetId(), $tick)) {
                continue;
            }

            if ($before !== null && $before->hasFleetById($fleet->getFleetId(), $before->getDefendingFleets())) {
                $beforeFleet = $before->getFleetById($fleet->getFleetId(), $before->getDefendingFleets());
                $battle->addDefendingFleet(
                    new Fleet($fleet->getFleetId(), $beforeFleet->getUnits(), $beforeFleet->getData())
                );
                continue;
            }

            $battle->addDefendingFleet(clone $fleet);
        }

        return $battle;
    }
}
