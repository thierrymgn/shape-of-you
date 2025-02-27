<?php

namespace App\Entity;

use App\Enum\AnalysisType;
use App\Repository\AiAnalysisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AiAnalysisRepository::class)]
class AiAnalysis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: AnalysisType::class)]
    private ?AnalysisType $analysisType = null;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column]
    private array $results = [];

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $confidenceScore = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'OutfitId')]
    private ?Outfit $OutfitId = null;

    #[ORM\ManyToOne(inversedBy: 'WardrobeItemId')]
    private ?WardrobeItem $wardrobeItemId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getAnalysisType(): ?AnalysisType
    {
        return $this->analysisType;
    }

    public function setAnalysisType(AnalysisType $analysisType): static
    {
        $this->analysisType = $analysisType;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param array<string, mixed> $results
     */
    public function setResults(array $results): static
    {
        $this->results = $results;

        return $this;
    }

    public function getConfidenceScore(): ?string
    {
        return $this->confidenceScore;
    }

    public function setConfidenceScore(string $confidenceScore): static
    {
        $this->confidenceScore = $confidenceScore;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOutfitId(): ?Outfit
    {
        return $this->OutfitId;
    }

    public function setOutfitId(?Outfit $OutfitId): static
    {
        $this->OutfitId = $OutfitId;

        return $this;
    }

    public function getWardrobeItemId(): ?WardrobeItem
    {
        return $this->wardrobeItemId;
    }

    public function setWardrobeItemId(?WardrobeItem $wardrobeItemId): static
    {
        $this->wardrobeItemId = $wardrobeItemId;

        return $this;
    }
}
