<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use App\Entity\PartnerOrder;
use App\Entity\User;
use App\Enum\PartnerOrderStatus;
use App\Enum\PartnerStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PartnerOrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@example.com')
             ->setFirstName('John')
             ->setLastName('Doe')
             ->setPassword('password');
        $manager->persist($user);

        $partner = new Partner();
        $partner->setName('Partner One')
                ->setWebsiteUrl('https://partnerone.com')
                ->setStatus(PartnerStatus::ACTIVE)
                ->setCreatedAt(new \DateTimeImmutable('now'))
                ->setUpdatedAt(new \DateTimeImmutable('now'));
        $manager->persist($partner);

        $order = new PartnerOrder();
        $order->setOrderReference('ORD-001')
              ->setTotalAmount('150.00')
              ->setStatus(PartnerOrderStatus::PENDING)
              ->setCreatedAt(new \DateTimeImmutable())
              ->setUpdatedAt(new \DateTimeImmutable())
              ->setUserId($user)
              ->setPartnerId($partner);
        $manager->persist($order);

        $manager->flush();
    }
}
