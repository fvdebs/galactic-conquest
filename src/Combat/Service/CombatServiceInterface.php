<?php

declare(strict_types=1);

namespace GC\Combat\Service;

use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\SettingsInterface;
use GC\Combat\Report\CombatReportGeneratorResponseInterface;
use GC\Universe\Model\Universe;

interface CombatServiceInterface
{
    /**
     * @param \GC\Combat\Model\BattleInterface $battle
     * @param \GC\Combat\Model\SettingsInterface $settings
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface
     */
    public function calculate(BattleInterface $battle, SettingsInterface $settings): CalculatorResponseInterface;

    /**
     * @param \GC\Combat\Calculator\CalculatorResponseInterface $calculatorResult
     *
     * @return string
     */
    public function formatToJson(CalculatorResponseInterface $calculatorResult) : string;

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
    ): CombatReportGeneratorResponseInterface;

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Combat\Model\SettingsInterface
     */
    public function mapSettingsFromUniverse(Universe $universe): SettingsInterface;

    /**
     * @param \GC\Combat\Service\CombatSimulationRequestInterface $simulationRequest
     *
     * @return \GC\Combat\Calculator\CalculatorResponseInterface[]
     */
    public function simulate(CombatSimulationRequestInterface $simulationRequest): array;
}
