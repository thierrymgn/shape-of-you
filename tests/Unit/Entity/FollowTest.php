<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Follow;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class FollowTest extends TestCase
{
    private Follow $follow;

    protected function setUp(): void
    {
        $this->follow = new Follow();
    }

    public function testSetAndGetCreatedAt(): void
    {
        $date = new \DateTimeImmutable('2025-02-14 12:00:00');
        $this->follow->setCreatedAt($date);
        $this->assertEquals($date, $this->follow->getCreatedAt());
    }

    public function testSetAndGetFollower(): void
    {
        $userFollower = new User();
        $userFollower->setEmail('follower@example.com')
                     ->setFirstName('John')
                     ->setLastName('Doe')
                     ->setPassword('password'); // Pour le test, la valeur en clair suffit

        $this->follow->setFollower($userFollower);
        $this->assertSame($userFollower, $this->follow->getFollower());
    }

    public function testSetAndGetFollowing(): void
    {
        $userFollowing = new User();
        $userFollowing->setEmail('following@example.com')
                      ->setFirstName('Jane')
                      ->setLastName('Smith')
                      ->setPassword('password');

        $this->follow->setFollowing($userFollowing);
        $this->assertSame($userFollowing, $this->follow->getFollowing());
    }
}
