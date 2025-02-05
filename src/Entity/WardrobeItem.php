<?php

namespace App\Entity;

use App\Enum\WardrobeSeason;
use App\Enum\WardrobeStatus;
use App\Repository\WardrobeItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: WardrobeItemRepository::class)]
#[Vich\Uploadable]
class WardrobeItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $brand = null;

    #[ORM\Column(length: 20)]
    private ?string $size = null;

    #[ORM\Column(length: 50)]
    private ?string $color = null;

    #[Vich\UploadableField(mapping: 'wardrobe_items', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, enumType: WardrobeStatus::class)]
    private WardrobeStatus $status = WardrobeStatus::ACTIVE;

    #[ORM\Column(length: 255, enumType: WardrobeSeason::class)]
    private WardrobeSeason $season = WardrobeSeason::ALL;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'wardrobeItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\ManyToOne(inversedBy: 'wardrobeItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * @var Collection<int, OutfitItem>
     */
    #[ORM\OneToMany(targetEntity: OutfitItem::class, mappedBy: 'wardrobeItem', orphanRemoval: true)]
    private Collection $outfitItems;

    /**
     * @var Collection<int, WardrobeItemTag>
     */
    #[ORM\OneToMany(targetEntity: WardrobeItemTag::class, mappedBy: 'wardrobeItem', orphanRemoval: true)]
    private Collection $wardrobeItemTags;

    public function __construct()
    {
        $this->status = WardrobeStatus::ACTIVE;
        $this->season = WardrobeSeason::ALL;
        $this->outfitItems = new ArrayCollection();
        $this->wardrobeItemTags = new ArrayCollection();
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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStatus(): WardrobeStatus
    {
        return $this->status;
    }

    public function setStatus(WardrobeStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSeason(): WardrobeSeason
    {
        return $this->season;
    }

    public function setSeason(WardrobeSeason $season): self
    {
        $this->season = $season;

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

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, OutfitItem>
     */
    public function getOutfitItems(): Collection
    {
        return $this->outfitItems;
    }

    public function addOutfitItem(OutfitItem $outfitItem): static
    {
        if (!$this->outfitItems->contains($outfitItem)) {
            $this->outfitItems->add($outfitItem);
            $outfitItem->setWardrobeItem($this);
        }

        return $this;
    }

    public function removeOutfitItem(OutfitItem $outfitItem): static
    {
        if ($this->outfitItems->removeElement($outfitItem)) {
            // set the owning side to null (unless already changed)
            if ($outfitItem->getWardrobeItem() === $this) {
                $outfitItem->setWardrobeItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WardrobeItemTag>
     */
    public function getWardrobeItemTags(): Collection
    {
        return $this->wardrobeItemTags;
    }

    public function addWardrobeItemTag(WardrobeItemTag $wardrobeItemTag): static
    {
        if (!$this->wardrobeItemTags->contains($wardrobeItemTag)) {
            $this->wardrobeItemTags->add($wardrobeItemTag);
            $wardrobeItemTag->setWardrobeItem($this);
        }

        return $this;
    }

    public function removeWardrobeItemTag(WardrobeItemTag $wardrobeItemTag): static
    {
        if ($this->wardrobeItemTags->removeElement($wardrobeItemTag)) {
            // set the owning side to null (unless already changed)
            if ($wardrobeItemTag->getWardrobeItem() === $this) {
                $wardrobeItemTag->setWardrobeItem(null);
            }
        }

        return $this;
    }
}
