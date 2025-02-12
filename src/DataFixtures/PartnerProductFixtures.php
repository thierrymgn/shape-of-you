<?php

namespace App\DataFixtures;

use App\Entity\PartnerProduct;
use App\Enum\StockStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PartnerProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product1 = new PartnerProduct();
        $product1->setName('Product One')
            ->setDescription('This is the description for product one.')
            ->setPrice('100.00')
            ->setSalePrice('90.00')
            ->setProductUrl('https://example.com/product-one')
            ->setImageUrl('https://example.com/images/product-one.jpg')
            ->setCategory('Electronics')
            ->setBrand('Brand One')
            ->setStockStatus(StockStatus::INSTOCK)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($product1);

        $product2 = new PartnerProduct();
        $product2->setName('Product Two')
            ->setDescription('This is the description for product two.')
            ->setPrice('200.00')
            ->setSalePrice(null)
            ->setProductUrl('https://example.com/product-two')
            ->setImageUrl('https://example.com/images/product-two.jpg')
            ->setCategory('Home Appliances')
            ->setBrand('Brand Two')
            ->setStockStatus(StockStatus::OUTOFSTOCK)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($product2);

        $product3 = new PartnerProduct();
        $product3->setName('Product three')
            ->setDescription('This is the description for product three.')
            ->setPrice('201.00')
            ->setSalePrice(null)
            ->setProductUrl('https://example.com/product-three')
            ->setImageUrl('https://example.com/images/product-three.jpg')
            ->setCategory('Home Appliances')
            ->setBrand('Brand Two')
            ->setStockStatus(StockStatus::LIMITED)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($product3);

        $manager->flush();
    }
}
