<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Partner;
use App\Entity\PartnerOrder;
use App\Entity\User;
use App\Enum\PartnerOrderStatus;
use PHPUnit\Framework\TestCase;

class PartnerOrderTest extends TestCase
{
    private PartnerOrder $partnerOrder;

    protected function setUp(): void
    {
        $this->partnerOrder = new PartnerOrder();
    }

    public function testSetAndGetOrderReference(): void
    {
        $this->partnerOrder->setOrderReference('ORD-001');
        $this->assertEquals('ORD-001', $this->partnerOrder->getOrderReference());
    }

    public function testSetAndGetTotalAmount(): void
    {
        $this->partnerOrder->setTotalAmount('150.00');
        $this->assertEquals('150.00', $this->partnerOrder->getTotalAmount());
    }

    public function testSetAndGetCommissionAmount(): void
    {
        $this->partnerOrder->setCommissionAmount('15.00');
        $this->assertEquals('15.00', $this->partnerOrder->getCommissionAmount());
    }

    public function testSetAndGetStatus(): void
    {
        $this->partnerOrder->setStatus(PartnerOrderStatus::PENDING);
        $this->assertEquals(PartnerOrderStatus::PENDING, $this->partnerOrder->getStatus());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $createdAt = new \DateTimeImmutable('2025-01-01 12:00:00');
        $this->partnerOrder->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->partnerOrder->getCreatedAt());
    }

    public function testSetAndGetUpdatedAt(): void
    {
        $updatedAt = new \DateTimeImmutable('2025-01-02 12:00:00');
        $this->partnerOrder->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->partnerOrder->getUpdatedAt());
    }

    public function testSetAndGetUserId(): void
    {
        $user = new User();
        $user->setEmail('user@example.com')
             ->setFirstName('John')
             ->setLastName('Doe')
             ->setPassword('password');
        $this->partnerOrder->setUserId($user);
        $this->assertSame($user, $this->partnerOrder->getUserId());
    }

    public function testSetAndGetPartnerId(): void
    {
        $partner = new Partner();
        $partner->setName('Partner One')
                ->setWebsiteUrl('https://partnerone.com');
        $this->partnerOrder->setPartnerId($partner);
        $this->assertSame($partner, $this->partnerOrder->getPartnerId());
    }
}
