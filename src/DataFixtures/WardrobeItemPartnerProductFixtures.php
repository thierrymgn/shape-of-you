<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\PartnerProduct;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Entity\WardrobeItemPartnerProduct;
use App\Enum\StockStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WardrobeItemPartnerProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur minimal pour le WardrobeItem
        $user = new User();
        $user->setEmail('user@example.com')
             ->setFirstName('Alice')
             ->setLastName('Dupont')
             ->setPassword('password'); // En clair pour le test
        $manager->persist($user);

        // Création d'une catégorie minimale pour le WardrobeItem
        $category = new Category();
        $category->setName('Vêtements');
        $manager->persist($category);

        // Création d'un WardrobeItem minimal en renseignant les champs obligatoires
        $wardrobeItem = new WardrobeItem();
        $wardrobeItem->setName('T-Shirt Basique')
                     ->setSize('M')
                     ->setColor('Blue')
                     ->setCreatedAt(new \DateTimeImmutable())
                     ->setUpdatedAt(new \DateTimeImmutable())
                     ->setCustomer($user)
                     ->setCategory($category);
        // Les autres champs (description, brand, image, etc.) étant optionnels, on les laisse null ou avec leurs valeurs par défaut
        $manager->persist($wardrobeItem);

        // Création d'un PartnerProduct minimal
        $partnerProduct = new PartnerProduct();
        $partnerProduct->setName('Produit Test')
                       ->setPrice('100.00')
                       ->setProductUrl('https://example.com/produit-test')
                       ->setImageUrl('https://example.com/produit-test.jpg')
                       ->setCategory('Vêtements')
                       ->setBrand('MarqueTest')
                       ->setStockStatus(StockStatus::INSTOCK) // Assurez-vous que cette valeur existe dans votre enum StockStatus
                       ->setCreatedAt(new \DateTimeImmutable())
                       ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($partnerProduct);

        // Création de l'entité WardrobeItemPartnerProduct en renseignant tous les champs obligatoires
        $wardrobeItemPartnerProduct = new WardrobeItemPartnerProduct();
        $wardrobeItemPartnerProduct->setSimilarityScore('95.50')
                                   ->setCreatedAt(new \DateTimeImmutable())
                                   ->setWardrobeItemId($wardrobeItem)      // Association avec le WardrobeItem créé
                                   ->setPartnerProductId($partnerProduct);  // Association avec le PartnerProduct créé
        $manager->persist($wardrobeItemPartnerProduct);

        $manager->flush();
    }
}
