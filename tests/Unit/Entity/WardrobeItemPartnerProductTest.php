<?php

namespace App\Tests\Unit\Entity;

use App\Entity\PartnerProduct;
use App\Entity\WardrobeItem;
use App\Entity\WardrobeItemPartnerProduct;
use PHPUnit\Framework\TestCase;

class WardrobeItemPartnerProductTest extends TestCase
{
    private WardrobeItemPartnerProduct $entity;

    protected function setUp(): void
    {
        $this->entity = new WardrobeItemPartnerProduct();
    }

    public function testSetAndGetSimilarityScore(): void
    {
        $score = '98.75';
        $this->entity->setSimilarityScore($score);
        $this->assertEquals($score, $this->entity->getSimilarityScore());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $date = new \DateTimeImmutable('2025-01-01 12:00:00');
        $this->entity->setCreatedAt($date);
        $this->assertEquals($date, $this->entity->getCreatedAt());
    }

    public function testSetAndGetWardrobeItemId(): void
    {
        $wardrobeItem = new WardrobeItem();
        // Vous pouvez éventuellement initialiser quelques propriétés minimales sur $wardrobeItem
        $this->entity->setWardrobeItemId($wardrobeItem);
        $this->assertSame($wardrobeItem, $this->entity->getWardrobeItemId());
    }

    public function testSetAndGetPartnerProductId(): void
    {
        $partnerProduct = new PartnerProduct();
        // Vous pouvez éventuellement initialiser quelques propriétés minimales sur $partnerProduct
        $this->entity->setPartnerProductId($partnerProduct);
        $this->assertSame($partnerProduct, $this->entity->getPartnerProductId());
    }
}
