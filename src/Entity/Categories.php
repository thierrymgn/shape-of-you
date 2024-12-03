<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, OutfitElements>
     */
    #[ORM\OneToMany(targetEntity: OutfitElements::class, mappedBy: 'category')]
    private Collection $outfitElements;

    /**
     * @var Collection<int, PartnerLinks>
     */
    #[ORM\OneToMany(targetEntity: PartnerLinks::class, mappedBy: 'category')]
    private Collection $partnerLinks;

    public function __construct()
    {
        $this->outfitElements = new ArrayCollection();
        $this->partnerLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, OutfitElements>
     */
    public function getOutfitElements(): Collection
    {
        return $this->outfitElements;
    }

    public function addOutfitElement(OutfitElements $outfitElement): static
    {
        if (!$this->outfitElements->contains($outfitElement)) {
            $this->outfitElements->add($outfitElement);
            $outfitElement->setCategory($this);
        }

        return $this;
    }

    public function removeOutfitElement(OutfitElements $outfitElement): static
    {
        if ($this->outfitElements->removeElement($outfitElement)) {
            // set the owning side to null (unless already changed)
            if ($outfitElement->getCategory() === $this) {
                $outfitElement->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PartnerLinks>
     */
    public function getPartnerLinks(): Collection
    {
        return $this->partnerLinks;
    }

    public function addPartnerLink(PartnerLinks $partnerLink): static
    {
        if (!$this->partnerLinks->contains($partnerLink)) {
            $this->partnerLinks->add($partnerLink);
            $partnerLink->setCategory($this);
        }

        return $this;
    }

    public function removePartnerLink(PartnerLinks $partnerLink): static
    {
        if ($this->partnerLinks->removeElement($partnerLink)) {
            // set the owning side to null (unless already changed)
            if ($partnerLink->getCategory() === $this) {
                $partnerLink->setCategory(null);
            }
        }

        return $this;
    }
}
