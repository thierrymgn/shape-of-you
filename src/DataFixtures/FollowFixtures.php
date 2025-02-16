<?php

namespace App\DataFixtures;

use App\Entity\Follow;
use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FollowFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $follower = new User();
        $follower->setEmail('follower@example.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPassword('password'); // Pensez à hasher le mot de passe en production

        $profileFollower = new Profile();
        $profileFollower->setStylePreferences([])
            ->setColorPreferences([])
            ->setSizePreferences([]);
        $follower->setProfile($profileFollower);
        $manager->persist($profileFollower);
        $manager->persist($follower);

        $following = new User();
        $following->setEmail('following@example.com')
            ->setFirstName('Jane')
            ->setLastName('Smith')
            ->setPassword('password');

        $profileFollowing = new Profile();
        $profileFollowing->setStylePreferences([])
            ->setColorPreferences([])
            ->setSizePreferences([]);
        $following->setProfile($profileFollowing);
        $manager->persist($profileFollowing);
        $manager->persist($following);

        $follow = new Follow();
        $follow->setCreatedAt(new \DateTimeImmutable());
        $follow->setFollower($follower);
        $follow->setFollowing($following);
        $manager->persist($follow);

        $manager->flush();
    }
}
