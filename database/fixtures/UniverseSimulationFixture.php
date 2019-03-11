<?php

declare(strict_types=1);

namespace GCFixture;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use GC\Alliance\Model\Alliance;
use GC\Faction\Model\Faction;
use GC\Galaxy\Model\Galaxy;
use GC\Player\Model\Player;
use GC\Technology\Model\Technology;
use GC\Unit\Model\Unit;
use GC\Universe\Model\Universe;
use GC\User\Model\User;

class UniverseSimulationFixture extends AbstractFixture
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \GC\User\Model\User
     */
    private $permanentUsers;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var \GC\Faction\Model\Faction
     */
    private $human;

    /**
     * @var \GC\Faction\Model\Faction
     */
    private $alien;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techRubium;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techPulsar;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techCoon;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techCenturion;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techHorus;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techLeo;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techAquilae;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techFornax;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techDraco;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techGoron;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techPentalin;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techZenit;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techCleptor;

    /**
     * @var \GC\Technology\Model\Technology
     */
    private $techCancri;

    /**
     * @var \GC\Unit\Model\Unit[]
     */
    private $units = [];

    /**
     * @var \GC\Technology\Model\Technology[]
     */
    private $playerTechnologies = [];

    /**
     * @var \GC\Technology\Model\Technology[]
     */
    private $galaxyTechnologies = [];

    /**
     * @var \GC\Technology\Model\Technology[]
     */
    private $allianceTechnologies = [];

    /**
     * @var \GC\Faction\Model\Faction[]
     */
    private $factions = [];

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @throws \Exception
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        // config
        $galaxyAndAlliancesMultiplier = 50;
        $universes = ['Sirius', 'Eridanus', 'Starman'];
        $this->createPermanentUser('John Doe', 'example@example.org');

        // create universes
        $this->output();
        foreach ($universes as $index => $universeName) {
            $this->output("====================== $universeName ==========================\n");

            $universe = $this->createUniverse($universeName);
            $universe = $this->fillUniverseWithRandomValues($universe);
            $universe = $this->createUniverseDefaults($universe);

            /*
            if ($this->randomBool()) {
                $universe = $this->createPermanentPlayersAndAssignToUniverse($universe);
            }
            */

            $universe = $this->createRandomGalaxiesAndAlliances($universe, $galaxyAndAlliancesMultiplier);

            $this->output("Generating alliance and galaxy ranking.\n");
            $universe->generateAllianceAndGalaxyRanking();

            $this->output("flushing...\n");
            $manager->flush();
        }
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Universe\Model\Universe
     */
    private function createPermanentPlayersAndAssignToUniverse(Universe $universe): Universe
    {
        foreach ($this->permanentUsers as $user) {
            $this->createPermanentUserPlayer($universe, $user);
        }

        return $universe;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param int $galaxyAndAlliancesMultiplier
     *
     * @return \GC\Universe\Model\Universe
     */
    private function createRandomGalaxiesAndAlliances(Universe $universe, int $galaxyAndAlliancesMultiplier): Universe
    {
        for ($i = 0; $i < $galaxyAndAlliancesMultiplier; $i++) {
            switch (\rand(1, 4)) {
                case 1:
                    $this->createPrivateGalaxiesInAlliance(
                        $universe,
                        1,
                        $this->getRandomAllianceGalaxyValue($universe),
                        $this->getRandomPrivateGalaxyPlayerValue($universe)
                    );
                break;
                case 2:
                    $this->createPrivateGalaxies(
                        $universe,
                        $this->getRandomNumberOfGalaxyCreationPerIteration(),
                        $this->getRandomPrivateGalaxyPlayerValue($universe)
                    );
                break;
                case 3:
                case 4:
                    $this->createPublicGalaxies(
                        $universe,
                        $this->getRandomNumberOfGalaxyCreationPerIteration(),
                        $this->getRandomPublicGalaxyPlayerValue($universe)
                    );
                break;
            }
        }

        return $universe;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    private function output(string $message = ''): void
    {
        echo $message . "\n";
    }

    /**
     * @return bool
     */
    private function randomBool(): bool
    {
        return (bool) $this->randomValueFrom([true, false]);
    }

    /**
     * @param mixed[] $array
     *
     * @return mixed
     */
    private function randomValueFrom(array $array)
    {
        $key = \array_rand($array);

        return $array[$key];
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return int
     */
    private function getRandomPrivateGalaxyPlayerValue(Universe $universe): int
    {
        return \rand(4, $universe->getMaxPrivateGalaxyPlayers());
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return int
     */
    private function getRandomAllianceGalaxyValue(Universe $universe): int
    {
        return \rand(1, $universe->getMaxAllianceGalaxies());
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return int
     */
    private function getRandomPublicGalaxyPlayerValue(Universe $universe): int
    {
        return \rand(5, $universe->getMaxPublicGalaxyPlayers());
    }

    /**
     * @return int
     */
    private function getRandomExtractorValue(): int
    {
        return \rand(1, 500);
    }

    /**
     * @return int
     */
    private function getRandomNumberOfGalaxyCreationPerIteration(): int
    {
        return \rand(1, 4);
    }

    /**
     * @return int
     */
    private function getRandomResourceValue(): int
    {
        return \rand(5000000, 50000000);
    }

    /**
     * @return int
     */
    private function getRandomResourceProductionValue(): int
    {
        return \rand(1000, 10000);
    }

    /**
     * @return int
     */
    private function getRandomTicksValue(): int
    {
        return \rand(2, 10);
    }

    /**
     * @return int
     */
    private function getRandomCostValue(): int
    {
        return \rand(500, 2000000);
    }

    /**
     * @return int
     */
    private function getRandomTaxValue(): int
    {
        return \rand(0, 50);
    }

    /**
     * @return \GC\Technology\Model\Technology
     */
    private function getRandomGalaxyTechnology(): Technology
    {
        return $this->randomValueFrom($this->galaxyTechnologies);
    }

    /**
     * @return \GC\Technology\Model\Technology
     */
    private function getRandomAllianceTechnology(): Technology
    {
        return $this->randomValueFrom($this->allianceTechnologies);
    }

    /**
     * @param \GC\User\Model\User $user
     */
    private function addPermanentUser(User $user): void
    {
        $this->permanentUsers[$user->getName()] = $user;
    }

    /**
     * @return string
     */
    private function createPassword(): string
    {
        return \password_hash('secret', PASSWORD_DEFAULT);
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Universe\Model\Universe
     */
    private function createUniverseDefaults(Universe $universe): Universe
    {
        $this->createDefaultUniverseFactions($universe);
        $this->createDefaultUniverseGalaxyTechnologies($universe);
        $this->createDefaultUniverseAllianceTechnologies($universe);
        $this->createDefaultUniversePlayerTechnologies($universe);
        $this->createDefaultUniverseUnits($universe);

        $this->output();

        return $universe;
    }

    /**
     * @param string $username
     * @param string $mail
     *
     * @return \GC\User\Model\User
     */
    private function createUser(string $username, string $mail): User
    {
        return new User($username, $mail, $this->createPassword());
    }
    /**
     * @param string $username
     * @param string $mail
     *
     * @return \GC\User\Model\User
     */
    private function createPermanentUser(string $username, string $mail): User
    {
        $this->output("Creating permanent user '$username' with mail '$mail' and password 'secret'");

        $user = $this->createUser($username, $mail);
        $this->addPermanentUser($user);

        $this->manager->persist($user);

        return $user;
    }

    /**
     * @param string $name
     *
     * @return \GC\Universe\Model\Universe
     */
    private function createUniverse(string $name): Universe
    {
        $universe = new Universe($name);
        $universe->activate();

        $this->manager->persist($universe);

        return $universe;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Universe\Model\Universe
     */
    private function fillUniverseWithRandomValues(Universe $universe): Universe
    {
        $universe->setDescription('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis.');
        $universe->setExtractorCrystalIncome(\rand(20, 100));
        $universe->setExtractorMetalIncome(\rand(20, 100));
        $universe->setMaxAllianceGalaxies(\rand(2, 6));
        $universe->setMaxPrivateGalaxyPlayers(\rand(5, 12));
        $universe->setMaxPublicGalaxyPlayers($universe->getMaxPrivateGalaxyPlayers() + \rand(3, 5));
        $universe->setScanBlockerMetalCost($this->getRandomCostValue());
        $universe->setScanBlockerCrystalCost($this->getRandomCostValue());
        $universe->setScanRelayMetalCost($this->getRandomCostValue());
        $universe->setScanRelayCrystalCost($this->getRandomCostValue());
        $universe->setRankingInterval($this->randomValueFrom([6, 12, 24]));
        $universe->setTickInterval($this->randomValueFrom([5, 10, 15]));
        $universe->setTicksStartingAt(new DateTime());

        return $universe;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param int $numberOfPrivateGalaxies
     * @param int $numberOfPlayers
     *
     * @return \GC\Universe\Model\Universe
     */
    private function createPrivateGalaxies(Universe $universe, int $numberOfPrivateGalaxies, int $numberOfPlayers): Universe
    {
        $this->output("Creating $numberOfPrivateGalaxies private galaxies with $numberOfPlayers players");
        $this->output();

        for ($i = 0; $i < $numberOfPrivateGalaxies; $i++) {
            $this->createPrivateGalaxy($universe, $numberOfPlayers);
        }

        return $universe;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param int $numberOfPlayers
     *
     * @return \GC\Galaxy\Model\Galaxy
     */
    private function createPrivateGalaxy(Universe $universe, int $numberOfPlayers)
    {
        $galaxy = $universe->createPrivateGalaxy();
        $galaxy = $this->fillGalaxyWithPlayers($galaxy, $numberOfPlayers);
        $galaxy = $this->fillGalaxyWithRandomValues($galaxy);

        return $galaxy;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param int $numberOfAlliances
     * @param int $numberOfPrivateGalaxies
     * @param int $numberOfPlayers
     *
     * @return \GC\Universe\Model\Universe
     */
    private function createPrivateGalaxiesInAlliance(Universe $universe, int $numberOfAlliances, int $numberOfPrivateGalaxies, int $numberOfPlayers): Universe
    {
        for ($a = 0; $a < $numberOfAlliances; $a++) {
            $this->output();
            $galaxy = $this->createPrivateGalaxy($universe, $numberOfPlayers);
            $alliance = $this->createAlliance($galaxy);

            if ($this->randomBool()) {
                $alliance->buildTechnology($this->getRandomAllianceTechnology());
            }

            $this->output("Creating alliance {$alliance->getName()} on galaxy {$galaxy->getNumber()}");

            for ($g = 0; $g < ($numberOfPrivateGalaxies - 1); $g++) {
                $galaxy = $this->createPrivateGalaxy($universe, $numberOfPlayers);

                $allianceApplication = $alliance->createAllianceApplicationFor($galaxy);
                $alliance->acceptAllianceApplication($allianceApplication);

                $this->output("Galaxy {$galaxy->getNumber()} added to alliance");
            }
        }

        $this->output();

        return $universe;
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return \GC\Alliance\Model\Alliance
     */
    private function createAlliance(Galaxy $galaxy): Alliance
    {
        $alliance = $galaxy->createAlliance($this->faker->company, $this->faker->companySuffix);
        $alliance = $this->fillAllianceWithRandomValues($alliance);

        return $alliance;
    }

    /**
     * @param \GC\Alliance\Model\Alliance $alliance
     *
     * @return \GC\Alliance\Model\Alliance
     */
    private function fillAllianceWithRandomValues(Alliance $alliance): Alliance
    {
        $alliance->setMetal($this->getRandomResourceValue());
        $alliance->setCrystal($this->getRandomResourceValue());
        $alliance->setTaxMetal($this->getRandomTaxValue());
        $alliance->setTaxCrystal($this->getRandomTaxValue());

        return $alliance;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param int $numberOfPublicGalaxies
     * @param int $numberOfPlayers
     *
     * @return \GC\Universe\Model\Universe
     */
    private function createPublicGalaxies(Universe $universe, int $numberOfPublicGalaxies, int $numberOfPlayers): Universe
    {
        $this->output("Creating $numberOfPublicGalaxies public galaxies with $numberOfPlayers players");
        $this->output();

        for ($i = 0; $i < $numberOfPublicGalaxies; $i++) {
            $galaxy = $universe->createPublicGalaxy();
            $galaxy = $this->fillGalaxyWithRandomValues($galaxy);
            $this->fillGalaxyWithPlayers($galaxy, $numberOfPlayers);
        }

        return $universe;
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return \GC\Galaxy\Model\Galaxy
     */
    private function fillGalaxyWithRandomValues(Galaxy $galaxy): Galaxy
    {
        $galaxy->setMetal($this->getRandomResourceValue());
        $galaxy->setCrystal($this->getRandomResourceValue());
        $galaxy->setTaxMetal($this->getRandomTaxValue());
        $galaxy->setTaxCrystal($this->getRandomTaxValue());

        if ($this->randomBool()) {
            $galaxy->buildTechnology($this->getRandomGalaxyTechnology());
        }

        if ($this->randomBool()) {
            $randomTechnologySecond = $this->getRandomGalaxyTechnology();
            if (!$galaxy->hasTechnology($randomTechnologySecond)) {
                $galaxy->buildTechnology($randomTechnologySecond);
            }
        }

        return $galaxy;
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     * @param int $numberOfPlayers
     *
     * @return \GC\Galaxy\Model\Galaxy
     */
    private function fillGalaxyWithPlayers(Galaxy $galaxy, int $numberOfPlayers): Galaxy
    {
        for ($i = 0; $i < $numberOfPlayers; $i++) {
            $this->createUserPlayer($galaxy, ($i === 0));
        }

        return $galaxy;
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     * @param bool $isCommander
     *
     * @return \GC\Player\Model\Player
     */
    private function createUserPlayer(Galaxy $galaxy, bool $isCommander = false): Player
    {
        $user = $this->createUser($this->faker->userName, $this->faker->email);
        $this->manager->persist($user);

        $player = $this->createPlayer($user, $galaxy, $galaxy->getUniverse()->getFactions()[0]);
        $player = $this->fillPlayerWithRandomValues($player);

        if ($isCommander) {
            $player->grantCommanderRole();
        }

        return $player;
    }

    /**
     * @param \GC\User\Model\User $user
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     * @param \GC\Faction\Model\Faction $faction
     *
     * @return \GC\Player\Model\Player
     */
    private function createPlayer(User $user, Galaxy $galaxy, Faction $faction): Player
    {
        $player = $galaxy->createPlayer($user, $faction);

        return $player;
    }

    /**
     * @param \GC\Player\Model\Player $player
     *
     * @return \GC\Player\Model\Player
     */
    private function fillPlayerWithRandomValues(Player $player): Player
    {
        $player->buildCrystalExtractors($this->getRandomExtractorValue());
        $player->buildMetalExtractors($this->getRandomExtractorValue());
        $player->increaseMetal($this->getRandomResourceValue());
        $player->increaseCrystal($this->getRandomResourceValue());

        if ($this->randomBool()) {
            $player->createPlayerCombatReport(\json_encode(['test' => 'todo']));
        }

        // add some technologies to player
        $randomPlayerTechnologies = $this->playerTechnologies;
        \shuffle($randomPlayerTechnologies);
        foreach($randomPlayerTechnologies as $randomPlayerTechnology) {
            $player->buildTechnology($randomPlayerTechnology);
            if ($this->randomBool()) {
                break;
            }
        }

        // add some units to player
        $randomUnits = $this->units;
        \shuffle($randomUnits);
        foreach($randomUnits as $randomUnit) {
            $player->buildUnit($randomUnit, \rand(1, 500));
            if ($this->randomBool()) {
                break;
            }
        }

        return $player;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    private function createDefaultUniverseFactions(Universe $universe): void
    {
        $this->output('Creating default factions.');
        $this->human = $universe->createFaction('faction.human', true);
        $this->alien = $universe->createFaction('faction.alien');
        $this->factions = [$this->human, $this->alien];
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    private function createDefaultUniversePlayerTechnologies(Universe $universe): void
    {
        $this->output('Creating default player technolgies.');

        $colony = $this->createPlayerHumanTechnology($universe, 'technology.colony');
        $colony->setMetalProduction($this->getRandomResourceProductionValue());
        $colony->setCrystalProduction($this->getRandomResourceProductionValue());

        $drive = $this->createPlayerHumanTechnology($universe, 'technology.drive');
        $trade = $this->createPlayerHumanTechnology($universe, 'technology.trading.outpost', Technology::FEATURE_PLAYER_TRADE);

        $this->techRubium = $this->createPlayerHumanTechnology($universe, 'technology.rubium');
        $this->techPulsar = $this->createPlayerHumanTechnology($universe, 'technology.pulsar');
        $this->techCoon = $this->createPlayerHumanTechnology($universe, 'technology.coon');
        $this->techCenturion = $this->createPlayerHumanTechnology($universe, 'technology.centurion');
        $this->techHorus = $this->createPlayerHumanTechnology($universe, 'technology.horus');
        $this->techLeo = $this->createPlayerHumanTechnology($universe, 'technology.leo');
        $this->techAquilae = $this->createPlayerHumanTechnology($universe, 'technology.aquilae');
        $this->techFornax = $this->createPlayerHumanTechnology($universe, 'technology.fornax');
        $this->techDraco = $this->createPlayerHumanTechnology($universe, 'technology.draco');
        $this->techGoron = $this->createPlayerHumanTechnology($universe, 'technology.goron');
        $this->techPentalin = $this->createPlayerHumanTechnology($universe, 'technology.pentalin');
        $this->techZenit = $this->createPlayerHumanTechnology($universe, 'technology.zenit');
        $this->techCleptor = $this->createPlayerHumanTechnology($universe, 'technology.cleptor');
        $this->techCancri = $this->createPlayerHumanTechnology($universe, 'technology.cancri');

        $this->playerTechnologies = [
            $colony, $drive, $trade, $this->techRubium, $this->techPulsar, $this->techCoon, $this->techCoon, $this->techCenturion,
            $this->techHorus, $this->techLeo, $this->techAquilae, $this->techFornax, $this->techDraco, $this->techGoron,
            $this->techPentalin, $this->techZenit, $this->techCleptor, $this->techCancri,
        ];

        // tech conditions
        $trade->addTechnologyCondition($colony);
        $drive->addTechnologyCondition($colony);
        $this->techRubium->addTechnologyCondition($colony);
        $this->techRubium->addTechnologyCondition($drive);
        $this->techPulsar->addTechnologyCondition($colony);
        $this->techPulsar->addTechnologyCondition($drive);
        $this->techCoon->addTechnologyCondition($colony);
        $this->techCoon->addTechnologyCondition($drive);
        $this->techCenturion->addTechnologyCondition($colony);
        $this->techCenturion->addTechnologyCondition($drive);
        $this->techHorus->addTechnologyCondition($colony);
        $this->techHorus->addTechnologyCondition($drive);
        $this->techLeo->addTechnologyCondition($colony);
        $this->techLeo->addTechnologyCondition($drive);
        $this->techAquilae->addTechnologyCondition($colony);
        $this->techAquilae->addTechnologyCondition($drive);
        $this->techFornax->addTechnologyCondition($colony);
        $this->techFornax->addTechnologyCondition($drive);
        $this->techDraco->addTechnologyCondition($colony);
        $this->techDraco->addTechnologyCondition($drive);
        $this->techGoron->addTechnologyCondition($colony);
        $this->techGoron->addTechnologyCondition($drive);
        $this->techPentalin->addTechnologyCondition($colony);
        $this->techPentalin->addTechnologyCondition($drive);
        $this->techZenit->addTechnologyCondition($colony);
        $this->techZenit->addTechnologyCondition($drive);
        $this->techCleptor->addTechnologyCondition($colony);
        $this->techCleptor->addTechnologyCondition($drive);
        $this->techCancri->addTechnologyCondition($colony);
        $this->techCancri->addTechnologyCondition($drive);
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param string $name
     * @param string|null $featureKey
     *
     * @return \GC\Technology\Model\Technology
     */
    private function createHumanTechnology(Universe $universe, string $name, ?string $featureKey = null): Technology
    {
        $technology = $universe->createTechnology($name, $this->human);
        $technology = $this->fillTechnologyWithRandomValues($technology);

        if ($featureKey !== null) {
            $technology->setFeatureKey($featureKey);
        }

        return $technology;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param string $name
     * @param string|null $featureKey
     *
     * @return \GC\Technology\Model\Technology
     */
    private function createGalaxyTechnology(Universe $universe, string $name, ?string $featureKey = null): Technology
    {
        $technology = $this->createHumanTechnology($universe, $name, $featureKey);
        $technology->setIsGalaxyTechnology(true);

        return $technology;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param string $name
     * @param string|null $featureKey
     *
     * @return \GC\Technology\Model\Technology
     */
    private function createPlayerHumanTechnology(Universe $universe, string $name, ?string $featureKey = null): Technology
    {
        $technology = $this->createHumanTechnology($universe, $name, $featureKey);
        $technology->setIsPlayerTechnology(true);

        return $technology;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param string $name
     * @param string|null $featureKey
     *
     * @return \GC\Technology\Model\Technology
     */
    private function createAllianceTechnology(Universe $universe, string $name, ?string $featureKey = null): Technology
    {
        $technology = $this->createHumanTechnology($universe, $name, $featureKey);
        $technology->setIsAllianceTechnology(true);

        return $technology;
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return \GC\Technology\Model\Technology
     */
    private function fillTechnologyWithRandomValues(Technology $technology): Technology
    {
        $technology->setMetalCost($this->getRandomCostValue());
        $technology->setCrystalCost($this->getRandomCostValue());
        $technology->setTicksToBuild($this->getRandomTicksValue());

        return $technology;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    private function createDefaultUniverseGalaxyTechnologies(Universe $universe): void
    {
        $this->output('Creating default galaxy technologies.');

        // technology
        $finance = $this->createGalaxyTechnology($universe, 'technology.galaxy.finance', Technology::FEATURE_GALAXY_FINANCE);
        $tacticFirst = $this->createGalaxyTechnology($universe, 'technology.galaxy.tactic.first', Technology::FEATURE_GALAXY_TACTIC);
        $tacticSecond = $this->createGalaxyTechnology($universe, 'technology.galaxy.tactic.second', Technology::FEATURE_GALAXY_TACTIC_INCOMING);
        $tacticThird = $this->createGalaxyTechnology($universe, 'technology.galaxy.tactic.third', Technology::FEATURE_GALAXY_TACTIC_FLEET);

        // conditions
        $tacticSecond->addTechnologyCondition($tacticFirst);
        $tacticThird->addTechnologyCondition($tacticSecond);

        $this->galaxyTechnologies = [$finance, $tacticFirst, $tacticSecond, $tacticSecond];
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    private function createDefaultUniverseAllianceTechnologies(Universe $universe): void
    {
        $this->output('Creating default alliance technolgies.');
        $finance = $this->createAllianceTechnology($universe, 'technology.alliance.finance', Technology::FEATURE_GALAXY_FINANCE);

        $this->allianceTechnologies = [$finance];
    }


    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param string $name
     *
     * @return \GC\Unit\Model\Unit
     */
    private function createHumanUnitStationary(Universe $universe, string $name): Unit
    {
        $unit = $universe->createUnit($name, $this->human);
        $unit->setIsStationary(true);
        $unit = $this->fillUnitWithRandomValues($unit);

        return $unit;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param string $name
     *
     * @return \GC\Unit\Model\Unit
     */
    private function createHumanUnit(Universe $universe, string $name): Unit
    {
        $unit = $universe->createUnit($name, $this->human);
        $unit = $this->fillUnitWithRandomValues($unit);

        return $unit;
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     *
     * @return \GC\Unit\Model\Unit
     */
    private function fillUnitWithRandomValues(Unit $unit): Unit
    {
        $unit->setMetalCost($this->getRandomCostValue());
        $unit->setCrystalCost($this->getRandomCostValue());
        $unit->setTicksToBuild($this->getRandomTicksValue());

        return $unit;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    private function createDefaultUniverseUnits(Universe $universe): void
    {
        $this->output('Creating default units.');

        $horus = $this->createHumanUnitStationary($universe, 'unit.horus');
        $horus->setRequiredTechnology($this->techHorus);

        $rubium = $this->createHumanUnitStationary($universe, 'unit.rubium');
        $rubium->setRequiredTechnology($this->techRubium);

        $pulsar = $this->createHumanUnitStationary($universe, 'unit.pulsar');
        $pulsar->setRequiredTechnology($this->techPulsar);

        $coon = $this->createHumanUnitStationary($universe, 'unit.coon');
        $coon->setRequiredTechnology($this->techCoon);

        $centurion = $this->createHumanUnitStationary($universe, 'unit.centurion');
        $centurion->setRequiredTechnology($this->techCenturion);

        $leo = $this->createHumanUnit($universe, 'unit.leo');
        $leo->setRequiredTechnology($this->techLeo);
        $leo->setCarrierSpaceConsumption(1);

        $aquilae = $this->createHumanUnit($universe, 'unit.aquilae');
        $aquilae->setRequiredTechnology($this->techAquilae);
        $aquilae->setCarrierSpaceConsumption(1);

        $fornax = $this->createHumanUnit($universe, 'unit.fornax');
        $fornax->setRequiredTechnology($this->techFornax);

        $draco = $this->createHumanUnit($universe, 'unit.draco');
        $draco->setRequiredTechnology($this->techDraco);

        $goron = $this->createHumanUnit($universe, 'unit.goron');
        $goron->setRequiredTechnology($this->techGoron);

        $pentalin = $this->createHumanUnit($universe, 'unit.pentalin');
        $pentalin->setRequiredTechnology($this->techPentalin);

        $zenit = $this->createHumanUnit($universe, 'unit.zenit');
        $zenit->setRequiredTechnology($this->techZenit);
        $zenit->setCarrierSpace(100);

        $cleptor = $this->createHumanUnit($universe, 'unit.cleptor');
        $cleptor->setRequiredTechnology($this->techCleptor);
        $cleptor->setExtractorStealAmount(1);

        $canri = $this->createHumanUnit($universe, 'unit.cancri');
        $canri->setRequiredTechnology($this->techCancri);
        $canri->setExtractorGuardAmount(1);

        // unit combat settings
        $horus->addUnitCombatSetting($draco, 40, '0.0114');
        $horus->addUnitCombatSetting($cleptor, 60, '0.3200');
        $rubium->addUnitCombatSetting($leo, 60, '0.3');
        $rubium->addUnitCombatSetting($cleptor, 40, '1.28');
        $pulsar->addUnitCombatSetting($aquilae, 40, '1.2');
        $pulsar->addUnitCombatSetting($fornax, 60, '0.5334');
        $coon->addUnitCombatSetting($draco, 40, '0.9143 ');
        $coon->addUnitCombatSetting($goron, 60, '0.4267');
        $centurion->addUnitCombatSetting($pentalin, 50, '0.5 ');
        $centurion->addUnitCombatSetting($zenit, 50, '0.3774');
        $leo->addUnitCombatSetting($coon, 35, '0.0246');
        $leo->addUnitCombatSetting($aquilae, 30, '0.3920');
        $leo->addUnitCombatSetting($goron, 35, '0.0263');
        $aquilae->addUnitCombatSetting($centurion, 35, '0.0080');
        $aquilae->addUnitCombatSetting($pentalin, 35, '0.0100');
        $aquilae->addUnitCombatSetting($zenit, 30, '0.0075');
        $fornax->addUnitCombatSetting($horus, 60, '4.5');
        $fornax->addUnitCombatSetting($leo, 40, '0.85');
        $draco->addUnitCombatSetting($rubium, 60, '3.5');
        $draco->addUnitCombatSetting($fornax, 40, '1.2444');
        $goron->addUnitCombatSetting($pulsar, 35, '3.5');
        $goron->addUnitCombatSetting($draco, 30, '0.8571');
        $goron->addUnitCombatSetting($canri, 35, '10');
        $pentalin->addUnitCombatSetting($coon, 20, '1');
        $pentalin->addUnitCombatSetting($goron, 20, '1.0666');
        $pentalin->addUnitCombatSetting($pentalin, 20, '0.4');
        $pentalin->addUnitCombatSetting($zenit, 20, '0.3019');
        $pentalin->addUnitCombatSetting($canri, 20, '26.6667 ');
        $zenit->addUnitCombatSetting($cleptor, 50, '25');
        $zenit->addUnitCombatSetting($canri, 50, '14');

        $this->units = [$horus, $rubium, $pulsar, $coon, $centurion, $leo, $aquilae, $fornax, $draco, $goron, $pentalin, $zenit];
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @param \GC\User\Model\User $user
     * @return \GC\Player\Model\Player
     */
    private function createPermanentUserPlayer(Universe $universe, User $user): Player
    {
        $this->output('Creating permanent user players.');

        $galaxy = $universe->getRandomPublicGalaxyWithFreeSpace();
        if ($galaxy === null) {
            $galaxy = $universe->createPublicGalaxy();
        }

        $player = $this->createPlayer($user, $galaxy, $this->human);
        $player = $this->fillPlayerWithRandomValues($player);

        return $player;
    }
}