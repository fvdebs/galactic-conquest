<?php

declare(strict_types=1);

namespace GCFixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use GC\Faction\Model\Faction;
use GC\Galaxy\Model\Galaxy;
use GC\Player\Model\Player;
use GC\Player\Model\PlayerFleet;
use GC\Technology\Model\Technology;
use GC\Technology\Model\TechnologyRelation;
use GC\Unit\Model\Unit;
use GC\Unit\Model\UnitCombat;
use GC\Unit\Model\UnitTechnologyCondition;
use GC\Universe\Model\Universe;
use GC\User\Model\User;

class TestDataFixture extends AbstractFixture
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        //$faker = Factory::create();
        // user
        $userFirst = new User('testuser', 'testuser@example.org', \password_hash('secret', PASSWORD_DEFAULT));
        $userSecond = new User('testuser2', 'testuser2@example.org', \password_hash('secret', PASSWORD_DEFAULT));
        $manager->persist($userFirst);
        $manager->persist($userSecond);

        // universe
        $universeFirst = new Universe('Hauptrunde');
        $universeSecond = new Universe('Speedrunde', null, 5);
        $manager->persist($universeFirst);
        $manager->persist($universeSecond);

        // faction
        $factionFirst = new Faction('Mensch');
        $factionSecond = new Faction('Alien');
        $manager->persist($factionFirst);
        $manager->persist($factionSecond);

        // technology
        $technologyFirst = new Technology('Zerstörer Forschung', $factionFirst);
        $technologyFirst->setFeatureKey('zerris');
        $technologySecond = new Technology('Kreuzer Forschung', $factionFirst);
        $technologySecond->setFeatureKey('kreuzer');
        $technologyThird = new Technology('Fregatten Forschung', $factionFirst);
        $technologyThird->setFeatureKey('fregs');
        $manager->persist($technologyFirst);
        $manager->persist($technologySecond);
        $manager->persist($technologyThird);

        $manager->flush();

        // technology relation
        $technologyRelationFirst = new TechnologyRelation($technologyFirst, $technologySecond, TechnologyRelation::RELATION_DEPENDS);
        $technologyRelationSecond = new TechnologyRelation($technologySecond, $technologyThird, TechnologyRelation::RELATION_DEPENDS);
        $manager->persist($technologyRelationFirst);
        $manager->persist($technologyRelationSecond);

        // unit
        $unitFirst = new Unit('Zerstörer', $factionFirst);
        $unitSecond = new Unit('Kreuzer', $factionFirst);
        $unitThird = new Unit('Fregatte', $factionFirst);
        $manager->persist($unitFirst);
        $manager->persist($unitSecond);
        $manager->persist($unitThird);

        $manager->flush();

        // unit combat
        $unitCombatFirst = new UnitCombat($unitFirst, $unitSecond, 100, '0.75');
        $unitCombatSecond = new UnitCombat($unitSecond, $unitThird, 100, '0.5');
        $unitCombatThird = new UnitCombat($unitThird, $unitFirst, 100, '0.2');
        $manager->persist($unitCombatFirst);
        $manager->persist($unitCombatSecond);
        $manager->persist($unitCombatThird);

        // unit technology condition
        $unitTechnologyConditionFirst = new UnitTechnologyCondition($unitFirst, $technologyFirst);
        $unitTechnologyConditionSecond = new UnitTechnologyCondition($unitSecond, $technologySecond);
        $unitTechnologyConditionThird = new UnitTechnologyCondition($unitThird, $technologyThird);
        $manager->persist($unitTechnologyConditionFirst);
        $manager->persist($unitTechnologyConditionSecond);
        $manager->persist($unitTechnologyConditionThird);

        $manager->flush();

        // galaxy
        $galaxyFirst = new Galaxy(1);
        $galaxySecond = new Galaxy(2);
        $manager->persist($galaxyFirst);
        $manager->persist($galaxySecond);

        $manager->flush();

        // player
        $playerFirst = new Player($userFirst, $factionFirst, $universeFirst, $galaxyFirst, 1);
        $playerSecond = new Player($userSecond, $factionFirst, $universeFirst, $galaxySecond, 1);
        $manager->persist($playerFirst);
        $manager->persist($playerSecond);

        $manager->flush();

        // player fleet
        $playerFleetFirst = new PlayerFleet($playerFirst);
        $playerFleetSecond = new PlayerFleet($playerSecond);
        $manager->persist($playerFleetFirst);
        $manager->persist($playerFleetSecond);

        $manager->flush();
    }
}