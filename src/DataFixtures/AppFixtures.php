<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserPermissions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i=0; $i<100; $i++) {
            $user = new User();
            $user->setUsername("toto_" . $i);
            $user->setPassword("password_" . $i);
            $user->setEmail("toto_" . $i . "@gmail.com");
            // set created at randomly
            $user->setCreatedAt(new \DateTimeImmutable('now - ' . rand(1, 100) . ' days'));
            // set updated at randomly
            $user->setUpdatedAt(new \DateTimeImmutable('now - ' . rand(1, 100) . ' days'));
            // create default user permission
            $userPermission = new UserPermissions();
            $userPermission->setGameServerNumber(rand(1, 10));
            $userPermission->setGameServerResources(["Low", "Medium", "High"][rand(0, 2)]);
            $userPermission->setRequestMovies(rand(0, 100));
            $userPermission->setRequestSeries(rand(0, 100));
            $userPermission->setUpdatedAt(new \DateTimeImmutable('now - ' . rand(1, 100) . ' days'));
            $user->setUserPermission($userPermission);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
