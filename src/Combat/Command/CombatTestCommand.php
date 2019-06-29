<?php

declare(strict_types=1);

namespace GC\Combat\Command;

use Exception;
use GC\Combat\Model\Battle;
use GC\Combat\Model\BattleInterface;
use GC\Combat\Model\Fleet;
use GC\Combat\Model\Settings;
use GC\Combat\Model\SettingsInterface;
use GC\Combat\Model\Unit;
use GC\Combat\Model\UnitCombatSetting;
use GC\Combat\Service\CombatServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class CombatTestCommand extends Command
{
    private const COMMAND_NAME = 'app:combat:test';
    private const COMMAND_DESCRIPTION = 'Creates a combat test.';

    private const UNIT_ID_JAG = 1;
    private const UNIT_ID_BOM = 2;
    private const UNIT_ID_FRE = 3;
    private const UNIT_ID_ZER = 4;
    private const UNIT_ID_KRZ = 5;
    private const UNIT_ID_SS = 6;
    private const UNIT_ID_TR = 7;
    private const UNIT_ID_CA = 8;
    private const UNIT_ID_CL = 9;
    private const UNIT_ID_AJ = 10;
    private const UNIT_ID_RU = 11;
    private const UNIT_ID_PU = 12;
    private const UNIT_ID_CO = 13;
    private const UNIT_ID_CE = 14;

    private const DATA_KEY_USER_ID = 'userId';
    private const DATA_KEY_PLAYER_ID = 'playerId';
    private const DATA_KEY_NAME = 'name';
    private const DATA_KEY_GALAXY_ID = 'galaxyId';
    private const DATA_KEY_GALAXY_NUMBER = 'galaxyNumber';
    private const DATA_KEY_GALAXY_POSITION = 'galaxyPosition';

    private const TARGET_USER_ID = 1;
    private const TARGET_PLAYER_ID = 1;
    private const TARGET_NAME = 'Tegrat';
    private const TARGET_FLEET_STATIONARY_ID = 10;
    private const TARGET_FLEET_ORBIT_ID = 1;
    private const TARGET_FLEET_FIRST_ID = 2;
    private const TARGET_FLEET_SECOND_ID = 3;
    private const TARGET_GALAXY_ID = 1;
    private const TARGET_GALAXY_NUMBER = 1;
    private const TARGET_GALAXY_POSITION = 1;

    private const DEFENDER_FIRST_USER_ID = 2;
    private const DEFENDER_FIRST_PLAYER_ID = 2;
    private const DEFENDER_FIRST_NAME = 'Rednefed1';
    private const DEFENDER_FIRST_FLEET_FIRST_ID = 4;
    private const DEFENDER_FIRST_GALAXY_ID = 2;
    private const DEFENDER_FIRST_GALAXY_NUMBER = 2;
    private const DEFENDER_FIRST_GALAXY_POSITION = 1;

    private const DEFENDER_SECOND_USER_ID = 3;
    private const DEFENDER_SECOND_PLAYER_ID = 3;
    private const DEFENDER_SECOND_NAME = 'Rednefed2';
    private const DEFENDER_SECOND_FLEET_FIRST_ID = 5;
    private const DEFENDER_SECOND_FLEET_SECOND_ID = 6;
    private const DEFENDER_SECOND_GALAXY_ID = 2;
    private const DEFENDER_SECOND_GALAXY_NUMBER = 2;
    private const DEFENDER_SECOND_GALAXY_POSITION = 2;

    private const ATTACKER_FIRST_USER_ID = 4;
    private const ATTACKER_FIRST_PLAYER_ID = 4;
    private const ATTACKER_FIRST_NAME = 'Rekcatta1';
    private const ATTACKER_FIRST_FLEET_FIRST_ID = 7;
    private const ATTACKER_FIRST_FLEET_SECOND_ID = 8;
    private const ATTACKER_FIRST_GALAXY_ID = 3;
    private const ATTACKER_FIRST_GALAXY_NUMBER = 3;
    private const ATTACKER_FIRST_GALAXY_POSITION = 1;

    private const ATTACKER_SECOND_USER_ID = 5;
    private const ATTACKER_SECOND_PLAYER_ID = 5;
    private const ATTACKER_SECOND_NAME = 'Rekcatta2';
    private const ATTACKER_SECOND_FLEET_FIRST_ID = 9;
    private const ATTACKER_SECOND_GALAXY_ID = 3;
    private const ATTACKER_SECOND_GALAXY_NUMBER = 3;
    private const ATTACKER_SECOND_GALAXY_POSITION = 2;

    /**
     * @var \GC\Combat\Service\CombatServiceInterface
     */
    private $combatService;

    /**
     * @param \GC\Combat\Service\CombatServiceInterface $combatService
     */
    public function __construct(CombatServiceInterface $combatService)
    {
        parent::__construct(static::COMMAND_NAME);
        $this->combatService = $combatService;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp(static::COMMAND_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $settings = $this->createSettings();
            $battle = $this->createBattle(
                $this->createAttackingFleets(),
                $this->createDefendingFleets(),
                100,
                100,
                $this->createTargetData(),
                $this->createEnvironmentData()
            );

            $calculatorResponse = $this->combatService->calculate($battle, $settings);

            $json = $this->combatService->formatToJson($calculatorResponse/*, static::DATA_KEY_PLAYER_ID*/);

            file_put_contents(__DIR__ . '/data.json', $json);

        } catch (Exception $e) {
            echo  "\n" . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine() . "\n";
        } catch (Throwable $e) {
            echo  "\n" . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine() . "\n";
        }

        return 0;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $attackingFleets
     * @param \GC\Combat\Model\FleetInterface[] $defendingFleets - default: []
     * @param int $targetExtractorsMetal - default: 0
     * @param int $targetExtractorsCrystal - default: 0
     * @param string[] $targetData - default: []
     * @param string[] $data - default: []
     *
     * @return \GC\Combat\Model\BattleInterface
     */
    private function createBattle(
        $attackingFleets,
        $defendingFleets = [],
        int $targetExtractorsMetal = 0,
        int $targetExtractorsCrystal = 0,
        array $targetData = [],
        array $data = []
    ): BattleInterface
    {
        return new Battle(
            $attackingFleets,
            $defendingFleets,
            $targetExtractorsMetal,
            $targetExtractorsCrystal,
            $targetData,
            $data
        );
    }

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    private function createDefendingFleets(): array
    {
        $fleets = [];

        $fleets[] = new Fleet(
            static::TARGET_FLEET_STATIONARY_ID,
            $this->createFleetStationary(),
            $this->createTargetData()
        );

        $fleets[] = new Fleet(
            static::TARGET_FLEET_ORBIT_ID,
            $this->createFleet(),
            $this->createTargetData()
        );

        $fleets[] = new Fleet(
            static::TARGET_FLEET_FIRST_ID,
            $this->createFleet(),
            $this->createTargetData()
        );

        $fleets[] = new Fleet(
            static::TARGET_FLEET_SECOND_ID,
            $this->createFleet(),
            $this->createTargetData()
        );

        $fleets[] = new Fleet(
            static::DEFENDER_FIRST_FLEET_FIRST_ID,
            $this->createFleet(),
            $this->createFirstDefenderData()
        );

        $fleets[] = new Fleet(
            static::DEFENDER_SECOND_FLEET_FIRST_ID,
            $this->createFleet(),
            $this->createSecondDefenderData()
        );

        $fleets[] = new Fleet(
            static::DEFENDER_SECOND_FLEET_SECOND_ID,
            $this->createFleet(),
            $this->createSecondDefenderData()
        );

        return $fleets;
    }

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    private function createAttackingFleets(): array
    {
        $fleets = [];

        $fleets[] = new Fleet(
            static::ATTACKER_FIRST_FLEET_FIRST_ID,
            $this->createFleet(),
            $this->createFirstAttackerData()
        );

        $fleets[] = new Fleet(
            static::ATTACKER_FIRST_FLEET_SECOND_ID,
            $this->createFleetSecond(),
            $this->createFirstAttackerData()
        );

        $fleets[] = new Fleet(
            static::ATTACKER_SECOND_FLEET_FIRST_ID,
            $this->createFleet(),
            $this->createSecondAttackerData()
        );

        return $fleets;
    }

    /**
     * @return string[]
     */
    private function createEnvironmentData(): array
    {
        return [
            'universe' => 'Sirius',
            'universeId' => 1,
            'universeRouteName' => 'sirius',
            'currentTick' => 20,
        ];
    }

    /**
     * @return string[]
     */
    private function createTargetData(): array
    {
        return [
            static::DATA_KEY_USER_ID => self::TARGET_USER_ID,
            static::DATA_KEY_PLAYER_ID => self::TARGET_PLAYER_ID,
            static::DATA_KEY_NAME => self::TARGET_NAME,
            static::DATA_KEY_GALAXY_ID => static::TARGET_GALAXY_ID,
            static::DATA_KEY_GALAXY_NUMBER => static::TARGET_GALAXY_NUMBER,
            static::DATA_KEY_GALAXY_POSITION => static::TARGET_GALAXY_POSITION,
        ];
    }

    /**
     * @return string[]
     */
    private function createFirstDefenderData(): array
    {
        return [
            static::DATA_KEY_USER_ID => self::DEFENDER_FIRST_USER_ID,
            static::DATA_KEY_PLAYER_ID => self::DEFENDER_FIRST_PLAYER_ID,
            static::DATA_KEY_NAME => self::DEFENDER_FIRST_NAME,
            static::DATA_KEY_GALAXY_ID => static::DEFENDER_FIRST_GALAXY_ID,
            static::DATA_KEY_GALAXY_NUMBER => static::DEFENDER_FIRST_GALAXY_NUMBER,
            static::DATA_KEY_GALAXY_POSITION => static::DEFENDER_FIRST_GALAXY_POSITION,
        ];
    }

    /**
     * @return string[]
     */
    private function createSecondDefenderData(): array
    {
        return [
            static::DATA_KEY_USER_ID => self::DEFENDER_SECOND_USER_ID,
            static::DATA_KEY_PLAYER_ID => self::DEFENDER_SECOND_PLAYER_ID,
            static::DATA_KEY_NAME => self::DEFENDER_SECOND_NAME,
            static::DATA_KEY_GALAXY_ID => static::DEFENDER_SECOND_GALAXY_ID,
            static::DATA_KEY_GALAXY_NUMBER => static::DEFENDER_SECOND_GALAXY_NUMBER,
            static::DATA_KEY_GALAXY_POSITION => static::DEFENDER_SECOND_GALAXY_POSITION,
        ];
    }

    /**
     * @return string[]
     */
    private function createFirstAttackerData(): array
    {
        return [
            static::DATA_KEY_USER_ID => self::ATTACKER_FIRST_USER_ID,
            static::DATA_KEY_PLAYER_ID => self::ATTACKER_FIRST_PLAYER_ID,
            static::DATA_KEY_NAME => self::ATTACKER_FIRST_NAME,
            static::DATA_KEY_GALAXY_ID => static::ATTACKER_FIRST_GALAXY_ID,
            static::DATA_KEY_GALAXY_NUMBER => static::ATTACKER_FIRST_GALAXY_NUMBER,
            static::DATA_KEY_GALAXY_POSITION => static::ATTACKER_FIRST_GALAXY_POSITION,
        ];
    }

    /**
     * @return string[]
     */
    private function createSecondAttackerData(): array
    {
        return [
            static::DATA_KEY_USER_ID => self::ATTACKER_SECOND_USER_ID,
            static::DATA_KEY_PLAYER_ID => self::ATTACKER_SECOND_PLAYER_ID,
            static::DATA_KEY_NAME => self::ATTACKER_SECOND_NAME,
            static::DATA_KEY_GALAXY_ID => static::ATTACKER_SECOND_GALAXY_ID,
            static::DATA_KEY_GALAXY_NUMBER => static::ATTACKER_SECOND_GALAXY_NUMBER,
            static::DATA_KEY_GALAXY_POSITION => static::ATTACKER_SECOND_GALAXY_POSITION,
        ];
    }

    /**
     * @return int[]
     */
    private function createFleetStationary(): array
    {
        return [
            static::UNIT_ID_AJ => 100,
            static::UNIT_ID_RU => 100,
            static::UNIT_ID_PU => 100,
            static::UNIT_ID_CO => 100,
            static::UNIT_ID_CE => 100,
        ];
    }

    /**
     * @return int[]
     */
    private function createFleet(): array
    {
        return [
            static::UNIT_ID_JAG => 100,
            static::UNIT_ID_BOM => 100,
            static::UNIT_ID_FRE => 100,
            static::UNIT_ID_ZER => 100,
            static::UNIT_ID_KRZ => 100,
            static::UNIT_ID_SS => 100,
            static::UNIT_ID_TR => 100,
            static::UNIT_ID_CA => 100100,
            static::UNIT_ID_CL => 100000,
        ];
    }

    /**
     * @return int[]
     */
    private function createFleetSecond(): array
    {
        return [
            static::UNIT_ID_JAG => 10440,
            static::UNIT_ID_BOM => 10440,
            static::UNIT_ID_FRE => 1040,
            static::UNIT_ID_ZER => 1400,
            static::UNIT_ID_KRZ => 1400,
            static::UNIT_ID_SS => 1040,
            static::UNIT_ID_TR => 2,
            static::UNIT_ID_CA => 1040100,
            static::UNIT_ID_CL => 404000,
        ];
    }

    /**
     *
     * @return \GC\Combat\Model\SettingsInterface
     */
    private function createSettings(): SettingsInterface
    {
        $units = [
            new Unit(static::UNIT_ID_JAG, 'unit.leo', 4000, 6000, 0, 3, 0, 0, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_BOM, 'unit.aquilae', 2000, 8000, 0, 3, 0, 0, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_FRE, 'unit.fornax', 15000, 7500, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_ZER, 'unit.draco', 40000, 30000, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_KRZ, 'unit.goron', 65000, 85000, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_SS, 'unit.pentalin', 250000, 150000, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_TR, 'unit.zenit', 200000, 50000, 100, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_CL, 'unit.cleptor', 1500, 1000, 0, 0, 1, 0, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_CA, 'unit.cancri', 1000, 1500, 0, 0, 0, 1, \GC\Unit\Model\Unit::GROUPING_OFFENSIVE),
            new Unit(static::UNIT_ID_AJ, 'unit.horus', 1000, 1000, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_DEFENSE),
            new Unit(static::UNIT_ID_RU, 'unit.rubium', 6000, 2000, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_DEFENSE),
            new Unit(static::UNIT_ID_PU, 'unit.pulsar', 20000, 10000, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_DEFENSE),
            new Unit(static::UNIT_ID_CO, 'unit.coon', 60000, 100000, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_DEFENSE),
            new Unit(static::UNIT_ID_CE, 'unit.centurion', 200000, 300000, 0, 0, 0, 0, \GC\Unit\Model\Unit::GROUPING_DEFENSE),
        ];

        $unitCombatSettings = [
            new UnitCombatSetting(static::UNIT_ID_JAG, static::UNIT_ID_CO, 0.35, 0.0246),
            new UnitCombatSetting(static::UNIT_ID_JAG, static::UNIT_ID_BOM, 0.3, 0.3920),
            new UnitCombatSetting(static::UNIT_ID_JAG, static::UNIT_ID_KRZ, 0.35, 0.0263),
            new UnitCombatSetting(static::UNIT_ID_BOM, static::UNIT_ID_CE, 0.3, 0.0080),
            new UnitCombatSetting(static::UNIT_ID_BOM, static::UNIT_ID_SS, 0.3, 0.0100),
            new UnitCombatSetting(static::UNIT_ID_BOM, static::UNIT_ID_TR, 0.3, 0.0075),
            new UnitCombatSetting(static::UNIT_ID_FRE, static::UNIT_ID_AJ, 0.6, 4.5),
            new UnitCombatSetting(static::UNIT_ID_FRE, static::UNIT_ID_JAG, 0.4, 0.85),
            new UnitCombatSetting(static::UNIT_ID_ZER, static::UNIT_ID_RU, 0.6, 3.5),
            new UnitCombatSetting(static::UNIT_ID_ZER, static::UNIT_ID_FRE, 0.4, 1.2444),
            new UnitCombatSetting(static::UNIT_ID_KRZ, static::UNIT_ID_PU, 0.35, 2),
            new UnitCombatSetting(static::UNIT_ID_KRZ, static::UNIT_ID_ZER, 0.3, 0.8571),
            new UnitCombatSetting(static::UNIT_ID_KRZ, static::UNIT_ID_CA, 0.35, 10),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_CO, 0.2, 1),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_KRZ, 0.2, 1.0666),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_SS, 0.2, 0.4),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_TR, 0.2, 0.3019),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_CA, 0.2, 26.6667),
            new UnitCombatSetting(static::UNIT_ID_TR, static::UNIT_ID_CL, 0.5, 25),
            new UnitCombatSetting(static::UNIT_ID_TR, static::UNIT_ID_CA, 0.5, 14),
            new UnitCombatSetting(static::UNIT_ID_AJ, static::UNIT_ID_ZER, 0.4, 0.0114),
            new UnitCombatSetting(static::UNIT_ID_AJ, static::UNIT_ID_CL, 0.6, 0.32),
            new UnitCombatSetting(static::UNIT_ID_RU, static::UNIT_ID_JAG, 0.6, 0.3),
            new UnitCombatSetting(static::UNIT_ID_RU, static::UNIT_ID_CL, 0.4, 1.28),
            new UnitCombatSetting(static::UNIT_ID_PU, static::UNIT_ID_BOM, 0.4, 1.2),
            new UnitCombatSetting(static::UNIT_ID_PU, static::UNIT_ID_FRE, 0.6, 0.5334),
            new UnitCombatSetting(static::UNIT_ID_CO, static::UNIT_ID_ZER, 0.4, 0.9143),
            new UnitCombatSetting(static::UNIT_ID_CO, static::UNIT_ID_KRZ, 0.6, 0.5334),
            new UnitCombatSetting(static::UNIT_ID_CE, static::UNIT_ID_SS, 0.5, 0.5),
            new UnitCombatSetting(static::UNIT_ID_CE, static::UNIT_ID_TR, 0.5, 0.3774),
        ];

        return new Settings(0.1, 0.4, 0.2, 5, $units, $unitCombatSettings, true);
    }
}
