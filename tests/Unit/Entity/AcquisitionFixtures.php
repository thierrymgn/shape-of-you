<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Acquisition;
use App\Enum\AcquisitionCondition;
use PHPUnit\Framework\TestCase;

class AcquisitionTest extends TestCase
{
    private Acquisition $acquisition;

    protected function setUp(): void
    {
        $this->acquisition = new Acquisition();
    }

    public function testBasicProperties(): void
    {
        $purchaseDate = new \DateTime('2025-01-15');
        $price = '199.99';
        $store = 'Store One';
        $condition = AcquisitionCondition::NEW;
        $warrentyEnd = new \DateTime('2026-01-15');
        $receiptImage = 'receipt1.jpg';

        $this->acquisition
            ->setPurchaseDate($purchaseDate)
            ->setPrice($price)
            ->setStore($store)
            ->setCondition($condition)
            ->setWarrentyEnd($warrentyEnd)
            ->setReceiptImage($receiptImage);

        $this->assertEquals($purchaseDate, $this->acquisition->getPurchaseDate());
        $this->assertEquals($price, $this->acquisition->getPrice());
        $this->assertEquals($store, $this->acquisition->getStore());
        $this->assertEquals($condition, $this->acquisition->getCondition());
        $this->assertEquals($warrentyEnd, $this->acquisition->getWarrentyEnd());
        $this->assertEquals($receiptImage, $this->acquisition->getReceiptImage());
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->acquisition
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $this->assertEquals($now, $this->acquisition->getCreatedAt());
        $this->assertEquals($now, $this->acquisition->getUpdatedAt());
    }
}
