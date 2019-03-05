<?php

declare(strict_types=1);

namespace GCFixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use GC\Technology\Model\Technology;
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
        $universe->activate();
        $manager->persist($universe);

        // faction
        $human = $universe->createFaction('Human');
        $alien = $universe->createFaction('Alien');

        // tech galaxy
        $techtacticthird = $universe->createTechnology('Finance', $human);
        $techtacticthird->setMetalCost(500);
        $techtacticthird->setCrystalCost(300);
        $techtacticthird->setTicksToBuild(2);
        $techtacticthird->setFeatureKey(Technology::FEATURE_GALAXY_FINANCE);

        $techtacticfirst = $universe->createTechnology('Taktic I', $human);
        $techtacticthird->setIsGalaxyTechnology(true);
        $techtacticfirst->setMetalCost(500);
        $techtacticfirst->setCrystalCost(300);
        $techtacticfirst->setTicksToBuild(2);
        $techtacticfirst->setFeatureKey(Technology::FEATURE_GALAXY_TACTIC);

        $techtacticsecond = $universe->createTechnology('Taktic II', $human);
        $techtacticthird->setIsGalaxyTechnology(true);
        $techtacticsecond->setMetalCost(500);
        $techtacticsecond->setCrystalCost(300);
        $techtacticsecond->setTicksToBuild(2);
        $techtacticsecond->setFeatureKey(Technology::FEATURE_GALAXY_TACTIC_INCOMING);

        $techtacticthird = $universe->createTechnology('Taktic III', $human);
        $techtacticthird->setIsGalaxyTechnology(true);
        $techtacticthird->setMetalCost(500);
        $techtacticthird->setCrystalCost(300);
        $techtacticthird->setTicksToBuild(2);
        $techtacticthird->setFeatureKey(Technology::FEATURE_GALAXY_TACTIC_FLEET);

        // tech galaxy conditions
        $techtacticsecond->addTechnologyCondition($techtacticfirst);

        $techtacticthird->addTechnologyCondition($techtacticsecond);

        // tech alliance
        $techfinancealliance = $universe->createTechnology('Finance', $human);
        $techfinancealliance->setIsAllianceTechnology(true);
        $techfinancealliance->setMetalCost(500);
        $techfinancealliance->setCrystalCost(300);
        $techfinancealliance->setTicksToBuild(2);
        $techfinancealliance->setFeatureKey(Technology::FEATURE_ALLIANCE_FINANCE);

        // tech player
        $techcolony = $universe->createTechnology('Koloniezentrum', $human);
        $techcolony->setIsPlayerTechnology(true);
        $techcolony->setMetalProduction(1000);
        $techcolony->setCrystalProduction(1000);
        $techcolony->setMetalCost(500);
        $techcolony->setCrystalCost(300);
        $techcolony->setTicksToBuild(2);

        $techdrive = $universe->createTechnology('Antrieb', $human);
        $techdrive->setIsPlayerTechnology(true);
        $techdrive->setMetalCost(500);
        $techdrive->setCrystalCost(300);
        $techdrive->setTicksToBuild(2);

        $techtrade = $universe->createTechnology('Handelsposten', $human);
        $techtrade->setIsPlayerTechnology(true);
        $techtrade->setMetalCost(500);
        $techtrade->setCrystalCost(300);
        $techtrade->setTicksToBuild(2);
        $techtrade->setFeatureKey(Technology::FEATURE_PLAYER_TRADE);

        $techrubium = $universe->createTechnology('Rubium', $human);
        $techrubium->setIsPlayerTechnology(true);
        $techrubium->setMetalCost(500);
        $techrubium->setCrystalCost(300);
        $techrubium->setTicksToBuild(2);

        $techpulsar = $universe->createTechnology('Pulsar', $human);
        $techpulsar->setIsPlayerTechnology(true);
        $techpulsar->setMetalCost(500);
        $techpulsar->setCrystalCost(300);
        $techpulsar->setTicksToBuild(2);

        $techcoon = $universe->createTechnology('Coon', $human);
        $techcoon->setIsPlayerTechnology(true);
        $techcoon->setMetalCost(500);
        $techcoon->setCrystalCost(300);
        $techcoon->setTicksToBuild(2);

        $techcenturion = $universe->createTechnology('Centurion', $human);
        $techcenturion->setIsPlayerTechnology(true);
        $techcenturion->setMetalCost(500);
        $techcenturion->setCrystalCost(300);
        $techcenturion->setTicksToBuild(2);

        $techhorus = $universe->createTechnology('Horus', $human);
        $techhorus->setIsPlayerTechnology(true);
        $techhorus->setMetalCost(500);
        $techhorus->setCrystalCost(300);
        $techhorus->setTicksToBuild(2);

        $techleo = $universe->createTechnology('Leo', $human);
        $techleo->setIsPlayerTechnology(true);
        $techleo->setMetalCost(500);
        $techleo->setCrystalCost(300);
        $techleo->setTicksToBuild(2);

        $techaquilae = $universe->createTechnology('Aquilae', $human);
        $techaquilae->setIsPlayerTechnology(true);
        $techaquilae->setMetalCost(500);
        $techaquilae->setCrystalCost(300);
        $techaquilae->setTicksToBuild(2);

        $techfornax = $universe->createTechnology('Fornax', $human);
        $techfornax->setIsPlayerTechnology(true);
        $techfornax->setMetalCost(500);
        $techfornax->setCrystalCost(300);
        $techfornax->setTicksToBuild(2);

        $techdraco = $universe->createTechnology('Draco', $human);
        $techdraco->setIsPlayerTechnology(true);
        $techdraco->setMetalCost(500);
        $techdraco->setCrystalCost(300);
        $techdraco->setTicksToBuild(2);

        $techgoron = $universe->createTechnology('Goron', $human);
        $techgoron->setIsPlayerTechnology(true);
        $techgoron->setMetalCost(500);
        $techgoron->setCrystalCost(300);
        $techgoron->setTicksToBuild(2);

        $techpentalin = $universe->createTechnology('Pentalin', $human);
        $techpentalin->setIsPlayerTechnology(true);
        $techpentalin->setMetalCost(500);
        $techpentalin->setCrystalCost(300);
        $techpentalin->setTicksToBuild(2);

        $techzenit = $universe->createTechnology('Zenit', $human);
        $techzenit->setIsPlayerTechnology(true);
        $techzenit->setMetalCost(500);
        $techzenit->setCrystalCost(300);
        $techzenit->setTicksToBuild(2);

        $techcleptor = $universe->createTechnology('Cleptor', $human);
        $techcleptor->setIsPlayerTechnology(true);
        $techcleptor->setMetalCost(500);
        $techcleptor->setCrystalCost(300);
        $techcleptor->setTicksToBuild(2);

        $techcancri = $universe->createTechnology('Cancri', $human);
        $techcancri->setIsPlayerTechnology(true);
        $techcancri->setMetalCost(500);
        $techcancri->setCrystalCost(300);
        $techcancri->setTicksToBuild(2);

        // tech conditions
        $techtrade->addTechnologyCondition($techcolony);

        $techdrive->addTechnologyCondition($techcolony);

        $techrubium->addTechnologyCondition($techcolony);
        $techrubium->addTechnologyCondition($techdrive);

        $techpulsar->addTechnologyCondition($techcolony);
        $techpulsar->addTechnologyCondition($techdrive);

        $techcoon->addTechnologyCondition($techcolony);
        $techcoon->addTechnologyCondition($techdrive);

        $techcenturion->addTechnologyCondition($techcolony);
        $techcenturion->addTechnologyCondition($techdrive);

        $techhorus->addTechnologyCondition($techcolony);
        $techhorus->addTechnologyCondition($techdrive);

        $techleo->addTechnologyCondition($techcolony);
        $techleo->addTechnologyCondition($techdrive);

        $techaquilae->addTechnologyCondition($techcolony);
        $techaquilae->addTechnologyCondition($techdrive);

        $techfornax->addTechnologyCondition($techcolony);
        $techfornax->addTechnologyCondition($techdrive);

        $techdraco->addTechnologyCondition($techcolony);
        $techdraco->addTechnologyCondition($techdrive);

        $techgoron->addTechnologyCondition($techcolony);
        $techgoron->addTechnologyCondition($techdrive);

        $techpentalin->addTechnologyCondition($techcolony);
        $techpentalin->addTechnologyCondition($techdrive);

        $techzenit->addTechnologyCondition($techcolony);
        $techzenit->addTechnologyCondition($techdrive);

        $techcleptor->addTechnologyCondition($techcolony);
        $techcleptor->addTechnologyCondition($techdrive);

        $techcancri->addTechnologyCondition($techcolony);
        $techcancri->addTechnologyCondition($techdrive);

        // unit
        $unithorus = $universe->createUnit('Horus', $human);
        $unithorus->setRequiredTechnology($techhorus);
        $unithorus->setIsStationary(true);
        $unithorus->setCrystalCost(100);
        $unithorus->setMetalCost(100);
        $unithorus->setTicksToBuild(100);

        $unitrubium = $universe->createUnit('Rubium', $human);
        $unitrubium->setRequiredTechnology($techrubium);
        $unitrubium->setIsStationary(true);
        $unitrubium->setCrystalCost(100);
        $unitrubium->setMetalCost(100);
        $unitrubium->setTicksToBuild(100);

        $unitpulsar = $universe->createUnit('Pulsar', $human);
        $unitpulsar->setRequiredTechnology($techpulsar);
        $unitpulsar->setIsStationary(true);
        $unitpulsar->setCrystalCost(100);
        $unitpulsar->setMetalCost(100);
        $unitpulsar->setTicksToBuild(100);

        $unitcoon = $universe->createUnit('Coon', $human);
        $unitcoon->setRequiredTechnology($techcoon);
        $unitcoon->setIsStationary(true);
        $unitcoon->setCrystalCost(100);
        $unitcoon->setMetalCost(100);
        $unitcoon->setTicksToBuild(100);

        $unitcenturion = $universe->createUnit('Centurion', $human);
        $unitcenturion->setRequiredTechnology($techcenturion);
        $unitcenturion->setIsStationary(true);
        $unitcenturion->setCrystalCost(100);
        $unitcenturion->setMetalCost(100);
        $unitcenturion->setTicksToBuild(100);

        $unitleo = $universe->createUnit('Leo', $human);
        $unitleo->setRequiredTechnology($techleo);
        $unitleo->setCarrierSpaceConsumption(1);
        $unitleo->setCrystalCost(6000);
        $unitleo->setMetalCost(4000);
        $unitleo->setTicksToBuild(12);

        $unitaquilae = $universe->createUnit('Aquilae', $human);
        $unitaquilae->setRequiredTechnology($techaquilae);
        $unitaquilae->setCarrierSpaceConsumption(1);
        $unitaquilae->setCrystalCost(8000);
        $unitaquilae->setMetalCost(2000);
        $unitaquilae->setTicksToBuild(16);

        $unitfornax = $universe->createUnit('Fornax', $human);
        $unitfornax->setRequiredTechnology($techfornax);
        $unitfornax->setCrystalCost(7500);
        $unitfornax->setMetalCost(15000);
        $unitfornax->setTicksToBuild(28);

        $unitdraco = $universe->createUnit('Draco', $human);
        $unitdraco->setRequiredTechnology($techdraco);
        $unitdraco->setCrystalCost(30000);
        $unitdraco->setMetalCost(40000);
        $unitdraco->setTicksToBuild(56);

        $unitgoron = $universe->createUnit('Goron', $human);
        $unitgoron->setRequiredTechnology($techgoron);
        $unitgoron->setCrystalCost(85000);
        $unitgoron->setMetalCost(65000);
        $unitgoron->setTicksToBuild(112);

        $unitpentalin = $universe->createUnit('Pentalin', $human);
        $unitpentalin->setRequiredTechnology($techpentalin);
        $unitpentalin->setCrystalCost(150000);
        $unitpentalin->setMetalCost(250000);
        $unitpentalin->setTicksToBuild(192);

        $unitzenit = $universe->createUnit('Zenit', $human);
        $unitzenit->setRequiredTechnology($techzenit);
        $unitzenit->setCarrierSpace(100);
        $unitzenit->setCrystalCost(50000);
        $unitzenit->setMetalCost(200000);
        $unitzenit->setTicksToBuild(192);

        $unitcleptor = $universe->createUnit('Cleptor', $human);
        $unitcleptor->setRequiredTechnology($techcleptor);
        $unitcleptor->setExtractorStealAmount(1);
        $unitcleptor->setCrystalCost(1000);
        $unitcleptor->setMetalCost(1500);
        $unitcleptor->setTicksToBuild(2);

        $unitcancri = $universe->createUnit('Cancri', $human);
        $unitcancri->setRequiredTechnology($techcancri);
        $unitcancri->setExtractorGuardAmount(1);
        $unitcancri->setCrystalCost(1500);
        $unitcancri->setMetalCost(1000);
        $unitcancri->setTicksToBuild(40);

        // unit combat settings
        $unithorus->addUnitCombatSetting($unitdraco, 40, '0.0114');
        $unithorus->addUnitCombatSetting($unitcleptor, 60, '0.3200');

        $unitrubium->addUnitCombatSetting($unitleo, 60, '0.3');
        $unitrubium->addUnitCombatSetting($unitcleptor, 40, '1.28');

        $unitpulsar->addUnitCombatSetting($unitaquilae, 40, '1.2');
        $unitpulsar->addUnitCombatSetting($unitfornax, 60, '0.5334');

        $unitcoon->addUnitCombatSetting($unitdraco, 40, '0.9143 ');
        $unitcoon->addUnitCombatSetting($unitgoron, 60, '0.4267');

        $unitcenturion->addUnitCombatSetting($unitpentalin, 50, '0.5 ');
        $unitcenturion->addUnitCombatSetting($unitzenit, 50, '0.3774');

        $unitleo->addUnitCombatSetting($unitcoon, 35, '0.0246');
        $unitleo->addUnitCombatSetting($unitaquilae, 30, '0.3920');
        $unitleo->addUnitCombatSetting($unitgoron, 35, '0.0263');

        $unitaquilae->addUnitCombatSetting($unitcenturion, 35, '0.0080');
        $unitaquilae->addUnitCombatSetting($unitpentalin, 35, '0.0100');
        $unitaquilae->addUnitCombatSetting($unitzenit, 30, '0.0075');

        $unitfornax->addUnitCombatSetting($unithorus, 60, '4.5');
        $unitfornax->addUnitCombatSetting($unitleo, 40, '0.85');

        $unitdraco->addUnitCombatSetting($unitrubium, 60, '3.5');
        $unitdraco->addUnitCombatSetting($unitfornax, 40, '1.2444');

        $unitgoron->addUnitCombatSetting($unitpulsar, 35, '3.5');
        $unitgoron->addUnitCombatSetting($unitdraco, 30, '0.8571');
        $unitgoron->addUnitCombatSetting($unitcancri, 35, '10');

        $unitpentalin->addUnitCombatSetting($unitcoon, 20, '1');
        $unitpentalin->addUnitCombatSetting($unitgoron, 20, '1.0666');
        $unitpentalin->addUnitCombatSetting($unitpentalin, 20, '0.4');
        $unitpentalin->addUnitCombatSetting($unitzenit, 20, '0.3019');
        $unitpentalin->addUnitCombatSetting($unitcancri, 20, '26.6667 ');

        $unitzenit->addUnitCombatSetting($unitcleptor, 50, '25');
        $unitzenit->addUnitCombatSetting($unitcancri, 50, '14');

        // create user and galaxy
        $faker = Factory::create();
        for ($i=0; $i < 1; $i++) {
            $user = new User($faker->userName, $faker->email, \password_hash('secret', PASSWORD_DEFAULT));
            $manager->persist($user);

            $galaxy = $universe->createPrivateGalaxy();

            $player = $galaxy->createPlayer($user, $human);
            $player->grantCommanderRole();
            $player->buildCrystalExtractors(5);
        }

        // create user and public galaxy if no galaxy space is available
        $faker = Factory::create();
        for ($i=0; $i < 5; $i++) {
            $user = new User($faker->userName, $faker->email, \password_hash('secret', PASSWORD_DEFAULT));
            $manager->persist($user);

            $galaxy = $universe->getRandomPublicGalaxyWithFreeSpace();
            if ($galaxy === null) {
                $galaxy = $universe->createPublicGalaxy();
                $player = $galaxy->createPlayer($user, $human);
                $player->grantCommanderRole();
            } else {
                $player = $galaxy->createPlayer($user, $human);
            }

            $player->increaseMetal(100000000);
            $player->increaseCrystal(100000000);

            $player->buildMetalExtractors(100);
            $player->buildCrystalExtractors(100);

            $player->buildUnit($unitcleptor, 10000);
            $player->buildUnit($unitpentalin, 100);

            $player->buildTechnology($techcolony);
            $player->buildTechnology($techtrade);

            $galaxy->setMetal(5000000);
            $galaxy->setCrystal(5000000);
            $galaxy->setTaxMetal(10);
            $galaxy->setTaxCrystal(50);
            $galaxy->buildTechnology($techtacticfirst);

            $player->createPlayerCombatReport(\json_encode(['test' => 'test']));

            echo "current: $i {$user->getName()}\n";
        }

        foreach ($universe->getGalaxies() as $galaxy) {
            $alliance = $universe->createAlliance($faker->company, $faker->companySuffix, $galaxy);
            $alliance->setMetal(5000000);
            $alliance->setCrystal(5000000);
            $alliance->setTaxMetal(50);
            $alliance->setTaxCrystal(10);
            $alliance->buildTechnology($techfinancealliance);
        }

        $universe->generateAllianceAndGalaxyRanking();

        $manager->flush();
    }
}