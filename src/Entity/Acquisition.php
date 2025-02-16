<?php

namespace App\Entity;

use App\Enum\AcquisitionCondition;
use App\Repository\AcquisitionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcquisitionRepository::class)]
class Acquisition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $purchaseDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $store = null;

    #[ORM\Column(enumType: AcquisitionCondition::class)]
    private ?AcquisitionCondition $condition = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $warrentyEnd = null;

    #[ORM\Column(length: 255)]
    private ?string $receiptImage = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(\DateTimeInterface $purchaseDate): static
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStore(): ?string
    {
        return $this->store;
    }

    public function setStore(string $store): static
    {
        $this->store = $store;

        return $this;
    }

    public function getCondition(): ?AcquisitionCondition
    {
        return $this->condition;
    }

    public function setCondition(AcquisitionCondition $condition): static
    {
        $this->condition = $condition;

        return $this;
    }

    public function getWarrentyEnd(): ?\DateTimeInterface
    {
        return $this->warrentyEnd;
    }

    public function setWarrentyEnd(\DateTimeInterface $warrentyEnd): static
    {
        $this->warrentyEnd = $warrentyEnd;

        return $this;
    }

    public function getReceiptImage(): ?string
    {
        return $this->receiptImage;
    }

    public function setReceiptImage(string $receiptImage): static
    {
        $this->receiptImage = $receiptImage;

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
