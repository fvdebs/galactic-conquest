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
    private const ATTACKER_SECOND_NAME = 'Rekcatta1';
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
     * @throws \Throwable
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
                $this->createTargetData(),
                $this->createEnvironmentData()
            );

            $result = $this->combatService->calculate($battle, $settings);
            $json = $this->combatService->formatJson($result, $settings, static::DATA_KEY_PLAYER_ID);

            file_put_contents(__DIR__ . '/data.json', $json);
            $output->writeln($json);

        } catch (Exception $e) {
            echo $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine();
        } catch (Throwable $e) {
            echo $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine();
        }

        return 0;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $attackingFleets - default: []
     * @param \GC\Combat\Model\FleetInterface[] $defendingFleets - default: []
     * @param string[] $targetData
     * @param string[] $data
     * @param int $targetExtractorsMetal - default: 100
     * @param int $targetExtractorsCrystal - default: 100
     *
     * @return \GC\Combat\Model\BattleInterface
     */
    private function createBattle(
        $attackingFleets = [],
        $defendingFleets = [],
        array $targetData = [],
        array $data = [],
        int $targetExtractorsMetal = 100,
        int $targetExtractorsCrystal = 100
    ): BattleInterface  {
        return new Battle(
            $attackingFleets,
            $defendingFleets,
            $targetData,
            $data,
            $targetExtractorsMetal,
            $targetExtractorsCrystal
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
            $this->createFleet(),
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
            static::UNIT_ID_TR => 1,
            static::UNIT_ID_CA => 100,
            static::UNIT_ID_CL => 100,
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
     * @return string[]
     */
    private function createEnvironmentData(): array
    {
        return [
            'universe' => 'Sirius',
            'universeId' => 1,
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
     *
     * @return \GC\Combat\Model\SettingsInterface
     */
    private function createSettings(): SettingsInterface
    {
        $units = [
            new Unit(static::UNIT_ID_JAG, 'unit.fighter', 4000, 6000, 0, 1, 0, 0),
            new Unit(static::UNIT_ID_BOM, 'unit.fighter', 2000, 8000, 0, 1, 0, 0),
            new Unit(static::UNIT_ID_FRE, 'unit.frigate', 15000, 7500, 0, 0, 0, 0),
            new Unit(static::UNIT_ID_ZER, 'unit.destroyer', 40000, 30000, 0, 0, 0, 0),
            new Unit(static::UNIT_ID_KRZ, 'unit.cruiser', 65000, 85000, 0, 0, 0, 0),
            new Unit(static::UNIT_ID_SS, 'unit.battleship', 250000, 150000, 0, 0, 0, 0),
            new Unit(static::UNIT_ID_TR, 'unit.carrier', 200000, 50000, 100, 0, 0, 0),
            new Unit(static::UNIT_ID_CL, 'unit.cleptor', 1500, 1000, 0, 0, 1, 0),
            new Unit(static::UNIT_ID_CA, 'unit.cancri', 1000, 1500, 0, 0, 0, 1),
            new Unit(static::UNIT_ID_AJ, 'unit.interceptor', 1000, 1000, 0, 0, 0, 0),
            new Unit(static::UNIT_ID_RU, 'unit.rubium', 6000, 2000, 0, 0, 0, 0),
            new Unit(static::UNIT_ID_PU, 'unit.pulsar', 20000, 10000, 0, 0, 0, 0),
            new Unit(static::UNIT_ID_CO, 'unit.coon', 60000, 100000, 0, 0, 0, 0),
            new Unit(static::UNIT_ID_CE, 'unit.centurion', 200000, 300000, 0, 0, 0, 0),
        ];

        $unitCombatSettings = [
            new UnitCombatSetting(static::UNIT_ID_JAG, static::UNIT_ID_CO, 35, 0.0246),
            new UnitCombatSetting(static::UNIT_ID_JAG, static::UNIT_ID_BOM, 30, 0.3920),
            new UnitCombatSetting(static::UNIT_ID_JAG, static::UNIT_ID_KRZ, 35, 0.0263),
            new UnitCombatSetting(static::UNIT_ID_BOM, static::UNIT_ID_CE, 35, 0.0080),
            new UnitCombatSetting(static::UNIT_ID_BOM, static::UNIT_ID_SS, 35, 0.0100),
            new UnitCombatSetting(static::UNIT_ID_BOM, static::UNIT_ID_TR, 30, 0.0075),
            new UnitCombatSetting(static::UNIT_ID_FRE, static::UNIT_ID_AJ, 60, 4.5),
            new UnitCombatSetting(static::UNIT_ID_FRE, static::UNIT_ID_JAG, 40, 0.85),
            new UnitCombatSetting(static::UNIT_ID_ZER, static::UNIT_ID_RU, 60, 3.5),
            new UnitCombatSetting(static::UNIT_ID_ZER, static::UNIT_ID_FRE, 40, 1.2444),
            new UnitCombatSetting(static::UNIT_ID_KRZ, static::UNIT_ID_PU, 35, 2),
            new UnitCombatSetting(static::UNIT_ID_KRZ, static::UNIT_ID_ZER, 30, 0.8571),
            new UnitCombatSetting(static::UNIT_ID_KRZ, static::UNIT_ID_CA, 35, 10),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_CO, 20, 1),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_KRZ, 20, 1.0666),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_SS, 20, 0.4),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_TR, 20, 0.3019),
            new UnitCombatSetting(static::UNIT_ID_SS, static::UNIT_ID_CA, 20, 26.6667),
            new UnitCombatSetting(static::UNIT_ID_TR, static::UNIT_ID_CL, 50, 25),
            new UnitCombatSetting(static::UNIT_ID_TR, static::UNIT_ID_CA, 50, 14),
            new UnitCombatSetting(static::UNIT_ID_AJ, static::UNIT_ID_ZER, 40, 0.0114),
            new UnitCombatSetting(static::UNIT_ID_AJ, static::UNIT_ID_CL, 60, 0.32),
            new UnitCombatSetting(static::UNIT_ID_RU, static::UNIT_ID_JAG, 60, 0.3),
            new UnitCombatSetting(static::UNIT_ID_RU, static::UNIT_ID_CL, 40, 1.28),
            new UnitCombatSetting(static::UNIT_ID_PU, static::UNIT_ID_BOM, 40, 1.2),
            new UnitCombatSetting(static::UNIT_ID_PU, static::UNIT_ID_FRE, 60, 0.5334),
            new UnitCombatSetting(static::UNIT_ID_CO, static::UNIT_ID_ZER, 40, 0.9143),
            new UnitCombatSetting(static::UNIT_ID_CO, static::UNIT_ID_KRZ, 60, 0.5334),
            new UnitCombatSetting(static::UNIT_ID_CE, static::UNIT_ID_SS, 50, 0.5),
            new UnitCombatSetting(static::UNIT_ID_CE, static::UNIT_ID_TR, 50, 0.3774),
        ];

        return new Settings(10, 40, 20, 5, $units, $unitCombatSettings);
    }
}
