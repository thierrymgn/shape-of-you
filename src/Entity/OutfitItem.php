<?php

namespace App\Entity;

use App\Repository\OutfitItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: OutfitItemRepository::class)]
class OutfitItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'outfitItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Outfit $outfit = null;

    #[ORM\ManyToOne(inversedBy: 'outfitItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WardrobeItem $wardrobeItem = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getOutfit(): ?Outfit
    {
        return $this->outfit;
    }

    public function setOutfit(?Outfit $outfit): static
    {
        $this->outfit = $outfit;

        return $this;
    }

    public function getWardrobeItem(): ?WardrobeItem
    {
        return $this->wardrobeItem;
    }

    public function setWardrobeItem(?WardrobeItem $wardrobeItem): static
    {
        $this->wardrobeItem = $wardrobeItem;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
