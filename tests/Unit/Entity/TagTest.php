<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Tag;
use App\Entity\WardrobeItemTag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    private Tag $tag;

    protected function setUp(): void
    {
        $this->tag = new Tag();
    }

    public function testBasicProperties(): void
    {
        $this->tag
            ->setName('Summer')
            ->setSlug('summer');

        $this->assertEquals('Summer', $this->tag->getName());
        $this->assertEquals('summer', $this->tag->getSlug());
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->tag->setCreatedAt($now);

        $this->assertEquals($now, $this->tag->getCreatedAt());
    }

    public function testWardrobeItemTags(): void
    {
        $wardrobeItemTag = new WardrobeItemTag();

        $this->tag->addWardrobeItemTag($wardrobeItemTag);
        $this->assertCount(1, $this->tag->getWardrobeItemTags());
        $this->assertTrue($this->tag->getWardrobeItemTags()->contains($wardrobeItemTag));

        $this->tag->removeWardrobeItemTag($wardrobeItemTag);
        $this->assertCount(0, $this->tag->getWardrobeItemTags());
        $this->assertFalse($this->tag->getWardrobeItemTags()->contains($wardrobeItemTag));
    }
}
