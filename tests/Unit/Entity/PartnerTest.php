<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Partner;
use App\Enum\PartnerStatus;
use PHPUnit\Framework\TestCase;

class PartnerTest extends TestCase
{
    private Partner $partner;

    protected function setUp(): void
    {
        $this->partner = new Partner();
    }

    public function testBasicProperties(): void
    {
        $this->partner
            ->setName('Test Partner')
            ->setWebsiteUrl('https://example.com')
            ->setLogo('https://example.com/logo.png')
            ->setApiKey('testApiKey')
            ->setApiSecret('testApiSecret')
            ->setStatus(PartnerStatus::ACTIVE) // Adaptez selon vos valeurs d'enum
            ->setCommissionRate('12.34')
            ->setDescription('A test partner description.');

        $this->assertEquals('Test Partner', $this->partner->getName());
        $this->assertEquals('https://example.com', $this->partner->getWebsiteUrl());
        $this->assertEquals('https://example.com/logo.png', $this->partner->getLogo());
        $this->assertEquals('testApiKey', $this->partner->getApiKey());
        $this->assertEquals('testApiSecret', $this->partner->getApiSecret());
        $this->assertEquals(PartnerStatus::ACTIVE, $this->partner->getStatus());
        $this->assertEquals('12.34', $this->partner->getCommissionRate());
        $this->assertEquals('A test partner description.', $this->partner->getDescription());
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->partner
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
        $this->assertEquals($now, $this->partner->getCreatedAt());
        $this->assertEquals($now, $this->partner->getUpdatedAt());
    }
}
