<?php

namespace App\DataFixtures;

use App\Entity\Acquisition;
use App\Enum\AcquisitionCondition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AcquisitionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Première acquisition
        $acquisition1 = new Acquisition();
        $acquisition1->setPurchaseDate(new \DateTime('2025-01-15'))
            ->setPrice('199.99')
            ->setStore('Store One')
            ->setCondition(AcquisitionCondition::NEW)
            ->setWarrentyEnd(new \DateTime('2026-01-15'))
            ->setReceiptImage('receipt1.jpg')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($acquisition1);

        // Deuxième acquisition
        $acquisition2 = new Acquisition();
        $acquisition2->setPurchaseDate(new \DateTime('2025-02-20'))
            ->setPrice('299.99')
            ->setStore('Store Two')
            ->setCondition(AcquisitionCondition::USED)
            ->setWarrentyEnd(new \DateTime('2026-02-20'))
            ->setReceiptImage('receipt2.jpg')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($acquisition2);

        // Troisième acquisition
        $acquisition3 = new Acquisition();
        $acquisition3->setPurchaseDate(new \DateTime('2025-03-30'))
            ->setPrice('399.99')
            ->setStore('Store Three')
            ->setCondition(AcquisitionCondition::HANDMADE)
            ->setWarrentyEnd(new \DateTime('2026-03-30'))
            ->setReceiptImage('receipt3.jpg')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($acquisition3);

        $manager->flush();
    }
}
