<?php

namespace AppBundle\DataFixtures;

use Movie\MovieBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 users
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUsername('user_' . $i);
            $user->setEmail('user_' . $i . '@gmail.com');
            $user->setPassword('user_' . $i);
            $manager->persist($user);
        }

        $manager->flush();
    }
}