<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Enum\WardrobeSeason;
use App\Enum\WardrobeStatus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class WardrobeItemTest extends TestCase
{
    private WardrobeItem $wardrobeItem;
    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        $this->wardrobeItem = new WardrobeItem();
        $this->user = new User();
        $this->category = new Category();
    }

    public function testInitialState(): void
    {
        $this->assertEquals(WardrobeStatus::ACTIVE, $this->wardrobeItem->getStatus());
        $this->assertEquals(WardrobeSeason::ALL, $this->wardrobeItem->getSeason());
        $this->assertNull($this->wardrobeItem->getImage());
        $this->assertNull($this->wardrobeItem->getImageFile());
    }

    public function testBasicProperties(): void
    {
        $this->wardrobeItem
            ->setName('Blue T-Shirt')
            ->setDescription('A nice blue t-shirt')
            ->setBrand('Nike')
            ->setSize('M')
            ->setColor('Blue');

        $this->assertEquals('Blue T-Shirt', $this->wardrobeItem->getName());
        $this->assertEquals('A nice blue t-shirt', $this->wardrobeItem->getDescription());
        $this->assertEquals('Nike', $this->wardrobeItem->getBrand());
        $this->assertEquals('M', $this->wardrobeItem->getSize());
        $this->assertEquals('Blue', $this->wardrobeItem->getColor());
    }

    public function testEnumProperties(): void
    {
        $this->wardrobeItem
            ->setStatus(WardrobeStatus::ARCHIVED)
            ->setSeason(WardrobeSeason::SUMMER);

        $this->assertEquals(WardrobeStatus::ARCHIVED, $this->wardrobeItem->getStatus());
        $this->assertEquals(WardrobeSeason::SUMMER, $this->wardrobeItem->getSeason());
    }

    public function testRelations(): void
    {
        $this->wardrobeItem
            ->setCustomer($this->user)
            ->setCategory($this->category);

        $this->assertSame($this->user, $this->wardrobeItem->getCustomer());
        $this->assertSame($this->category, $this->wardrobeItem->getCategory());
    }

    public function testImage(): void
    {
        $mockFile = $this->createMock(File::class);

        $this->wardrobeItem
            ->setImage('test.jpg')
            ->setImageFile($mockFile);

        $this->assertEquals('test.jpg', $this->wardrobeItem->getImage());
        $this->assertSame($mockFile, $this->wardrobeItem->getImageFile());
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();

        $this->wardrobeItem
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $this->assertEquals($now, $this->wardrobeItem->getCreatedAt());
        $this->assertEquals($now, $this->wardrobeItem->getUpdatedAt());
    }
}
