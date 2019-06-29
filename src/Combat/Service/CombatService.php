<?php

declare(strict_types=1);

namespace GC\Combat\Service;

use GC\Combat\Calculator\CalculatorInterface;
use GC\Combat\Calculator\CalculatorResponseInterface;
use GC\Combat\Format\JsonFormatterInterface;
use GC\Combat\Mapper\SettingsMapperInterface;
use GC\Combat\Model\BattleInterface;
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
     * @param string|null $mergeByDataKey
     *
     * @return string
     */
    public function formatToJson(CalculatorResponseInterface $calculatorResult, ?string $mergeByDataKey = null): string
    {
        return $this->jsonFormatter->format($calculatorResult, $mergeByDataKey);
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
}
