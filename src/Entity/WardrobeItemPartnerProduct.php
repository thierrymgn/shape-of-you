<?php

namespace App\Entity;

use App\Repository\WardrobeItemPartnerProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WardrobeItemPartnerProductRepository::class)]
class WardrobeItemPartnerProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $similarityScore = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'wardrobeItemPartnerProducts')]
    private ?WardrobeItem $wardrobeItemId = null;

    #[ORM\ManyToOne(inversedBy: 'wardrobeItemPartnerProducts')]
    private ?PartnerProduct $partnerProductId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getSimilarityScore(): ?string
    {
        return $this->similarityScore;
    }

    public function setSimilarityScore(string $similarityScore): static
    {
        $this->similarityScore = $similarityScore;

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

    public function getWardrobeItemId(): ?WardrobeItem
    {
        return $this->wardrobeItemId;
    }

    public function setWardrobeItemId(?WardrobeItem $wardrobeItemId): static
    {
        $this->wardrobeItemId = $wardrobeItemId;

        return $this;
    }

    public function getPartnerProductId(): ?PartnerProduct
    {
        return $this->partnerProductId;
    }

    public function setPartnerProductId(?PartnerProduct $partnerProductId): static
    {
        $this->partnerProductId = $partnerProductId;

        return $this;
    }
}
