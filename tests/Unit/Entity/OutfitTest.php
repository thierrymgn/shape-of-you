<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Outfit;
use App\Entity\OutfitItem;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Enum\WardrobeSeason;
use PHPUnit\Framework\TestCase;

class OutfitTest extends TestCase
{
    private Outfit $outfit;

    protected function setUp(): void
    {
        $this->outfit = new Outfit();
    }

    public function testBasicProperties(): void
    {
        $this->outfit
            ->setName('Summer Casual')
            ->setDescription('Perfect for hot days')
            ->setOccasion('Casual')
            ->setSeason(WardrobeSeason::SUMMER)
            ->setPublic(true)
            ->setImage('path/to/image.jpg');

        $this->assertEquals('Summer Casual', $this->outfit->getName());
        $this->assertEquals('Perfect for hot days', $this->outfit->getDescription());
        $this->assertEquals('Casual', $this->outfit->getOccasion());
        $this->assertEquals(WardrobeSeason::SUMMER, $this->outfit->getSeason());
        $this->assertTrue($this->outfit->isPublic());
        $this->assertEquals('path/to/image.jpg', $this->outfit->getImage());
    }

    public function testUserRelation(): void
    {
        $user = new User();
        $this->outfit->setCustomer($user);

        $this->assertSame($user, $this->outfit->getCustomer());
    }

    public function testOutfitItems(): void
    {
        $wardrobeItem = new WardrobeItem();
        $outfitItem = new OutfitItem();
        $outfitItem->setWardrobeItem($wardrobeItem);
        $outfitItem->setPosition(1);
        $this->outfit->addOutfitItem($outfitItem);

        $this->assertCount(1, $this->outfit->getOutfitItems());
        $this->assertTrue($this->outfit->getOutfitItems()->contains($outfitItem));
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();

        $this->outfit
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $this->assertEquals($now, $this->outfit->getCreatedAt());
        $this->assertEquals($now, $this->outfit->getUpdatedAt());
    }
}
