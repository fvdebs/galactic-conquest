<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class UserDataLoader implements FixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /*
        $hasher = new \Inferno\Utility\PasswordHasher();
        $faker = Faker\Factory::create();

        $manager->persist(new User('Markus', $hasher->hash('secret'), 'example@example.org'));
        for ($i = 0; $i < 50; $i++) {
            $manager->persist(new User($faker->userName, $hasher->hash('secret'), $faker->email));
        }

        $manager->flush();
        */
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }
}
