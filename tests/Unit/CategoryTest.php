<?php

namespace App\Tests\Unit;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testCreateCategory(): void
    {
        $category = new Category();
        $category->setName('Test');

        $this->assertEquals('Test', $category->getName());
    }
}
