<?php

namespace App\Entity;

use App\Repository\OutfitHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutfitHistoryRepository::class)]
class OutfitHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $viewedAt = null;

    #[ORM\ManyToOne(inversedBy: 'outfitHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $customer = null;

    #[ORM\ManyToOne(inversedBy: 'outfitHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Outfits $outfit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getViewedAt(): ?\DateTimeInterface
    {
        return $this->viewedAt;
    }

    public function setViewedAt(\DateTimeInterface $viewedAt): static
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }

    public function getCustomer(): ?Users
    {
        return $this->customer;
    }

    public function setCustomer(?Users $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getOutfit(): ?Outfits
    {
        return $this->outfit;
    }

    public function setOutfit(?Outfits $outfit): static
    {
        $this->outfit = $outfit;

        return $this;
    }
}
