<?php

namespace App\Tests\Unit\Entity;

use App\Entity\PartnerProduct;
use App\Enum\StockStatus;
use PHPUnit\Framework\TestCase;

class PartnerProductTest extends TestCase
{
    private PartnerProduct $partnerProduct;

    protected function setUp(): void
    {
        $this->partnerProduct = new PartnerProduct();
    }

    public function testBasicProperties(): void
    {
        $this->partnerProduct
            ->setName('Test Product')
            ->setDescription('This is a description for the test product.')
            ->setPrice('100.00')
            ->setSalePrice('80.00')
            ->setProductUrl('https://example.com/test-product')
            ->setImageUrl('https://example.com/images/test-product.jpg')
            ->setCategory('Electronics')
            ->setBrand('Test Brand')
            ->setStockStatus(StockStatus::INSTOCK);

        $this->assertEquals('Test Product', $this->partnerProduct->getName());
        $this->assertEquals('This is a description for the test product.', $this->partnerProduct->getDescription());
        $this->assertEquals('100.00', $this->partnerProduct->getPrice());
        $this->assertEquals('80.00', $this->partnerProduct->getSalePrice());
        $this->assertEquals('https://example.com/test-product', $this->partnerProduct->getProductUrl());
        $this->assertEquals('https://example.com/images/test-product.jpg', $this->partnerProduct->getImageUrl());
        $this->assertEquals('Electronics', $this->partnerProduct->getCategory());
        $this->assertEquals('Test Brand', $this->partnerProduct->getBrand());
        $this->assertEquals(StockStatus::INSTOCK, $this->partnerProduct->getStockStatus());
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->partnerProduct
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $this->assertEquals($now, $this->partnerProduct->getCreatedAt());
        $this->assertEquals($now, $this->partnerProduct->getUpdatedAt());
    }
}
