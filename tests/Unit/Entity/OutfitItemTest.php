<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Outfit;
use App\Entity\OutfitItem;
use App\Entity\WardrobeItem;
use PHPUnit\Framework\TestCase;

class OutfitItemTest extends TestCase
{
    private OutfitItem $outfitItem;

    protected function setUp(): void
    {
        $this->outfitItem = new OutfitItem();
    }

    public function testRelations(): void
    {
        $outfit = new Outfit();
        $wardrobeItem = new WardrobeItem();

        $this->outfitItem
            ->setOutfit($outfit)
            ->setWardrobeItem($wardrobeItem)
            ->setPosition(1);

        $this->assertSame($outfit, $this->outfitItem->getOutfit());
        $this->assertSame($wardrobeItem, $this->outfitItem->getWardrobeItem());
        $this->assertEquals(1, $this->outfitItem->getPosition());
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();

        $this->outfitItem
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $this->assertEquals($now, $this->outfitItem->getCreatedAt());
        $this->assertEquals($now, $this->outfitItem->getUpdatedAt());
    }
}
