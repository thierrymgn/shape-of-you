<?php

namespace App\Tests\Unit\Entity;

use App\Entity\AiAnalysis;
use App\Entity\Outfit;
use App\Entity\WardrobeItem;
use App\Enum\AnalysisType;
use PHPUnit\Framework\TestCase;

class AiAnalysisTest extends TestCase
{
    private AiAnalysis $aiAnalysis;

    protected function setUp(): void
    {
        $this->aiAnalysis = new AiAnalysis();
    }

    public function testSetAndGetAnalysisType(): void
    {
        $this->aiAnalysis->setAnalysisType(AnalysisType::ITEM);
        $this->assertEquals(AnalysisType::ITEM, $this->aiAnalysis->getAnalysisType());
    }

    public function testSetAndGetResults(): void
    {
        $results = [
            'detail' => 'test value',
            'items' => [1, 2, 3],
        ];
        $this->aiAnalysis->setResults($results);
        $this->assertEquals($results, $this->aiAnalysis->getResults());
    }

    public function testSetAndGetConfidenceScore(): void
    {
        $score = '95.50';
        $this->aiAnalysis->setConfidenceScore($score);
        $this->assertEquals($score, $this->aiAnalysis->getConfidenceScore());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $date = new \DateTimeImmutable('2025-01-01 12:00:00');
        $this->aiAnalysis->setCreatedAt($date);
        $this->assertEquals($date, $this->aiAnalysis->getCreatedAt());
    }

    public function testSetAndGetOutfitId(): void
    {
        $outfit = new Outfit();
        // Pour le test, nous créons une instance minimaliste d'Outfit
        $this->aiAnalysis->setOutfitId($outfit);
        $this->assertSame($outfit, $this->aiAnalysis->getOutfitId());
    }

    public function testSetAndGetWardrobeItemId(): void
    {
        $wardrobeItem = new WardrobeItem();
        // Pour le test, nous créons une instance minimaliste de WardrobeItem
        $this->aiAnalysis->setWardrobeItemId($wardrobeItem);
        $this->assertSame($wardrobeItem, $this->aiAnalysis->getWardrobeItemId());
    }
}
