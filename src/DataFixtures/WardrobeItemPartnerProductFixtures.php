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
        $user = new User();
        $user->setEmail('userwardrobe@example.com')
             ->setFirstName('Alice')
             ->setLastName('Dupont')
             ->setPassword('password');
        $manager->persist($user);

        $category = new Category();
        $category->setName('Vêtements');
        $manager->persist($category);

        $wardrobeItem = new WardrobeItem();
        $wardrobeItem->setName('T-Shirt Basique')
                     ->setSize('M')
                     ->setColor('Blue')
                     ->setCreatedAt(new \DateTimeImmutable())
                     ->setUpdatedAt(new \DateTimeImmutable())
                     ->setCustomer($user)
                     ->setCategory($category);
        $manager->persist($wardrobeItem);

        $partnerProduct = new PartnerProduct();
        $partnerProduct->setName('Produit Test')
                       ->setPrice('100.00')
                       ->setProductUrl('https://example.com/produit-test')
                       ->setImageUrl('https://example.com/produit-test.jpg')
                       ->setCategory('Vêtements')
                       ->setBrand('MarqueTest')
                       ->setStockStatus(StockStatus::INSTOCK)
                       ->setCreatedAt(new \DateTimeImmutable())
                       ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($partnerProduct);

        $wardrobeItemPartnerProduct = new WardrobeItemPartnerProduct();
        $wardrobeItemPartnerProduct->setSimilarityScore('95.50')
                                   ->setCreatedAt(new \DateTimeImmutable())
                                   ->setWardrobeItemId($wardrobeItem)
                                   ->setPartnerProductId($partnerProduct);
        $manager->persist($wardrobeItemPartnerProduct);

        $manager->flush();
    }
}
