<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Tag;
use App\Entity\WardrobeItem;
use App\Entity\WardrobeItemTag;
use PHPUnit\Framework\TestCase;

class WardrobeItemTagTest extends TestCase
{
    private WardrobeItemTag $wardrobeItemTag;

    protected function setUp(): void
    {
        $this->wardrobeItemTag = new WardrobeItemTag();
    }

    public function testRelations(): void
    {
        $tag = new Tag();
        $wardrobeItem = new WardrobeItem();

        $this->wardrobeItemTag
            ->setTag($tag)
            ->setWardrobeItem($wardrobeItem);

        $this->assertSame($tag, $this->wardrobeItemTag->getTag());
        $this->assertSame($wardrobeItem, $this->wardrobeItemTag->getWardrobeItem());
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->wardrobeItemTag->setCreatedAt($now);

        $this->assertEquals($now, $this->wardrobeItemTag->getCreatedAt());
    }
}
