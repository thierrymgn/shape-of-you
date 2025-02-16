<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use App\Entity\PartnerOrder;
use App\Entity\User;
use App\Enum\PartnerOrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PartnerOrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur minimal (pour la relation userId)
        $user = new User();
        $user->setEmail('user@example.com')
             ->setFirstName('John')
             ->setLastName('Doe')
             ->setPassword('password'); // Pour le test, en clair (à hasher en production)
        $manager->persist($user);

        // Création d'un partenaire minimal (pour la relation partnerId)
        $partner = new Partner();
        $partner->setName('Partner One')
                ->setWebsiteUrl('https://partnerone.com');
        $manager->persist($partner);

        // Création d'une commande partenaire (PartnerOrder) avec les champs obligatoires
        $order = new PartnerOrder();
        $order->setOrderReference('ORD-001')
              ->setTotalAmount('150.00')
              // Adaptez la valeur en fonction des cas possibles dans votre enum
              ->setStatus(PartnerOrderStatus::PENDING)
              ->setCreatedAt(new \DateTimeImmutable())
              ->setUpdatedAt(new \DateTimeImmutable())
              ->setUserId($user)      // Association avec l'utilisateur
              ->setPartnerId($partner); // Association avec le partenaire
        $manager->persist($order);

        $manager->flush();
    }
}
