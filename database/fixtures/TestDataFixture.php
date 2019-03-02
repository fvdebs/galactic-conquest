<?php

declare(strict_types=1);

namespace GCFixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use GC\Faction\Model\Faction;
use GC\Galaxy\Model\Galaxy;
use GC\Player\Model\Player;
use GC\Player\Model\PlayerFleet;
use GC\Technology\Model\Technology;
use GC\Technology\Model\TechnologyCondition;
use GC\Unit\Model\Unit;
use GC\Unit\Model\UnitCombatSetting;
use GC\Universe\Model\Universe;
use GC\User\Model\User;

class TestDataFixture extends AbstractFixture
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @throws \Exception
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // universe
        $universe = new Universe('sirius');
        $manager->persist($universe);

        // faction
        $human = $universe->createFaction('Human');
        $alien = $universe->createFaction('Alien');

        // units
        $unitrubium = $universe->createUnit('Rubium', $human);
        $unitpulsar = $universe->createUnit('Pulsar', $human);
        $unitcoon = $universe->createUnit('Coon', $human);
        $unitcenturion = $universe->createUnit('Centurion', $human);
        $unithorus = $universe->createUnit('Horus', $human);
        $unitleo = $universe->createUnit('Leo', $human);
        $unitaquilae = $universe->createUnit('Aquilae', $human);
        $unitfornax = $universe->createUnit('Fornax', $human);
        $unitdraco = $universe->createUnit('Draco', $human);
        $unitgoron = $universe->createUnit('Goron', $human);
        $unitpentalin = $universe->createUnit('Pentalin', $human);
        $unitzenit = $universe->createUnit('Zenit', $human);
        $unitcancri = $universe->createUnit('Cancri', $human);

        // tech
        $techcolony = $universe->createTechnology('Koloniezentrum', $human);
        $techrubium = $universe->createTechnology('Rubium', $human);
        $techpulsar = $universe->createTechnology('Pulsar', $human);
        $techcoon = $universe->createTechnology('Coon', $human);
        $techcenturion = $universe->createTechnology('Centurion', $human);
        $techhorus = $universe->createTechnology('Horus', $human);
        $techleo = $universe->createTechnology('Leo', $human);
        $techaquilae = $universe->createTechnology('Aquilae', $human);
        $techfornax = $universe->createTechnology('Fornax', $human);
        $techdraco = $universe->createTechnology('Draco', $human);
        $techgoron = $universe->createTechnology('Goron', $human);
        $techpentalin = $universe->createTechnology('Pentalin', $human);
        $techzenit = $universe->createTechnology('Zenit', $human);
        $techcancri = $universe->createTechnology('Cancri', $human);

        // create user and galaxy
        $faker = Factory::create();
        for ($i=0; $i < 10; $i++) {
            $user = new User($faker->userName, $faker->email, \password_hash('secret', PASSWORD_DEFAULT));
            $manager->persist($user);

            $galaxy = $universe->createPrivateGalaxy();

            $player = $universe->createPlayer($user, $human, $galaxy);
            $player->grantCommanderRole();
            $player->buildCrystalExtractors(5);
        }

        // create user and public galaxy if no galaxy space is available
        $faker = Factory::create();
        for ($i=0; $i < 10; $i++) {
            $user = new User($faker->name, $faker->email, \password_hash('secret', PASSWORD_DEFAULT));
            $manager->persist($user);

            $galaxy = $universe->getRandomPublicGalaxyWithFreeSpace();
            if ($galaxy === null) {
                $galaxy = $universe->createPublicGalaxy();
                $player = $universe->createPlayer($user, $human, $galaxy);
                $player->grantCommanderRole();
            } else {
                $player = $universe->createPlayer($user, $human, $galaxy);
            }

            $player->buildMetalExtractors(2);
        }

        $universe->calculateRanking();

        $manager->flush();
    }
}