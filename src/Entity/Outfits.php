<?php

namespace App\Entity;

use App\Repository\OutfitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutfitsRepository::class)]
class Outfits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'outfits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $customer = null;

    /**
     * @var Collection<int, OutfitElements>
     */
    #[ORM\OneToMany(targetEntity: OutfitElements::class, mappedBy: 'outfit', orphanRemoval: true)]
    private Collection $outfitElements;

    /**
     * @var Collection<int, OutfitHistory>
     */
    #[ORM\OneToMany(targetEntity: OutfitHistory::class, mappedBy: 'outfit', orphanRemoval: true)]
    private Collection $outfitHistories;

    /**
     * @var Collection<int, SocialPosts>
     */
    #[ORM\OneToMany(targetEntity: SocialPosts::class, mappedBy: 'outfit', orphanRemoval: true)]
    private Collection $socialPosts;

    public function __construct()
    {
        $this->outfitElements = new ArrayCollection();
        $this->outfitHistories = new ArrayCollection();
        $this->socialPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getCustomer(): ?Users
    {
        return $this->customer;
    }

    public function setCustomer(?Users $customer): static
    {
        $this->customer = $customer;

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
            $outfitElement->setOutfit($this);
        }

        return $this;
    }

    public function removeOutfitElement(OutfitElements $outfitElement): static
    {
        if ($this->outfitElements->removeElement($outfitElement)) {
            // set the owning side to null (unless already changed)
            if ($outfitElement->getOutfit() === $this) {
                $outfitElement->setOutfit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OutfitHistory>
     */
    public function getOutfitHistories(): Collection
    {
        return $this->outfitHistories;
    }

    public function addOutfitHistory(OutfitHistory $outfitHistory): static
    {
        if (!$this->outfitHistories->contains($outfitHistory)) {
            $this->outfitHistories->add($outfitHistory);
            $outfitHistory->setOutfit($this);
        }

        return $this;
    }

    public function removeOutfitHistory(OutfitHistory $outfitHistory): static
    {
        if ($this->outfitHistories->removeElement($outfitHistory)) {
            // set the owning side to null (unless already changed)
            if ($outfitHistory->getOutfit() === $this) {
                $outfitHistory->setOutfit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SocialPosts>
     */
    public function getSocialPosts(): Collection
    {
        return $this->socialPosts;
    }

    public function addSocialPost(SocialPosts $socialPost): static
    {
        if (!$this->socialPosts->contains($socialPost)) {
            $this->socialPosts->add($socialPost);
            $socialPost->setOutfit($this);
        }

        return $this;
    }

    public function removeSocialPost(SocialPosts $socialPost): static
    {
        if ($this->socialPosts->removeElement($socialPost)) {
            // set the owning side to null (unless already changed)
            if ($socialPost->getOutfit() === $this) {
                $socialPost->setOutfit(null);
            }
        }

        return $this;
    }
}
