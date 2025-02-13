<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i<100; $i++) {
            $user = new Users();
            $user->setUsername("toto_" . $i);
            $user->setPassword("password_" . $i);
            $user->setEmail("toto_" . $i . "@gmail.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setIsVerified(true);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
