<?php

namespace App\Entity;

use App\Repository\WardrobeItemTagRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: WardrobeItemTagRepository::class)]
class WardrobeItemTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'wardrobeItemTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WardrobeItem $wardrobeItem = null;

    #[ORM\ManyToOne(inversedBy: 'wardrobeItemTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tag $tag = null;

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

    public function getWardrobeItem(): ?WardrobeItem
    {
        return $this->wardrobeItem;
    }

    public function setWardrobeItem(?WardrobeItem $wardrobeItem): static
    {
        $this->wardrobeItem = $wardrobeItem;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): static
    {
        $this->tag = $tag;

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
