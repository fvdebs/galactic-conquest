<?php

declare(strict_types=1);

namespace GCFixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Inferno\Demo\User\Model\User;

class UserFixture extends AbstractFixture
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        //$faker = Factory::create();

        // add persistent user
        $user = new User(
           'testuser',
            'example@example.org',
            \password_hash('secret', PASSWORD_DEFAULT)
        );
        $manager->persist($user);

        // add random users
        /*
        for ($i=0; $i < 10; $i++) {
            $user = new User(
                $faker->userName,
                $faker->email,
                \password_hash('secret', PASSWORD_DEFAULT)
            );
            $manager->persist($user);
        }
        */

        $manager->flush();
    }
}
