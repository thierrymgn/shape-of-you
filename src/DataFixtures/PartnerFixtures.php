<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use App\Enum\PartnerStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PartnerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $partner1 = new Partner();
        $partner1->setName('Partner One')
            ->setWebsiteUrl('https://partnerone.com')
            ->setLogo('https://partnerone.com/logo.png')
            ->setApiKey('apikey_partner1')
            ->setApiSecret('secret_partner1')
            ->setStatus(PartnerStatus::ACTIVE)
            ->setCommissionRate('10.50')
            ->setDescription('Description du premier partenaire.')
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setUpdatedAt(new \DateTimeImmutable('now'));
        $manager->persist($partner1);

        $partner2 = new Partner();
        $partner2->setName('Partner Two')
            ->setWebsiteUrl('https://partnertwo.com')
            ->setLogo(null)
            ->setApiKey(null)
            ->setApiSecret(null)
            ->setStatus(PartnerStatus::INACTIVE)
            ->setCommissionRate(null)
            ->setDescription('Description du second partenaire.')
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setUpdatedAt(new \DateTimeImmutable('now'));
        $manager->persist($partner2);

        $manager->flush();
    }
}
