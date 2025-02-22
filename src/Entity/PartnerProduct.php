<?php

namespace App\Entity;

use App\Enum\StockStatus;
use App\Repository\PartnerProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartnerProductRepository::class)]
class PartnerProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $salePrice = null;

    #[ORM\Column(length: 255)]
    private ?string $productUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

    #[ORM\Column(length: 100)]
    private ?string $brand = null;

    #[ORM\Column(enumType: StockStatus::class)]
    private ?StockStatus $stockStatus = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, WardrobeItemPartnerProduct>
     */
    #[ORM\OneToMany(targetEntity: WardrobeItemPartnerProduct::class, mappedBy: 'partnerProductId')]
    private Collection $wardrobeItemPartnerProducts;

    public function __construct()
    {
        $this->wardrobeItemPartnerProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getSalePrice(): ?string
    {
        return $this->salePrice;
    }

    public function setSalePrice(?string $salePrice): static
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    public function getProductUrl(): ?string
    {
        return $this->productUrl;
    }

    public function setProductUrl(string $productUrl): static
    {
        $this->productUrl = $productUrl;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getStockStatus(): ?StockStatus
    {
        return $this->stockStatus;
    }

    public function setStockStatus(StockStatus $stockStatus): static
    {
        $this->stockStatus = $stockStatus;

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

    /**
     * @return Collection<int, WardrobeItemPartnerProduct>
     */
    public function getWardrobeItemPartnerProducts(): Collection
    {
        return $this->wardrobeItemPartnerProducts;
    }

    public function addWardrobeItemPartnerProduct(WardrobeItemPartnerProduct $wardrobeItemPartnerProduct): static
    {
        if (!$this->wardrobeItemPartnerProducts->contains($wardrobeItemPartnerProduct)) {
            $this->wardrobeItemPartnerProducts->add($wardrobeItemPartnerProduct);
            $wardrobeItemPartnerProduct->setPartnerProductId($this);
        }

        return $this;
    }

    public function removeWardrobeItemPartnerProduct(WardrobeItemPartnerProduct $wardrobeItemPartnerProduct): static
    {
        if ($this->wardrobeItemPartnerProducts->removeElement($wardrobeItemPartnerProduct)) {
            // set the owning side to null (unless already changed)
            if ($wardrobeItemPartnerProduct->getPartnerProductId() === $this) {
                $wardrobeItemPartnerProduct->setPartnerProductId(null);
            }
        }

        return $this;
    }
}
