<?php

namespace App\Entity;

use App\Enum\WardrobeSeason;
use App\Repository\OutfitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OutfitRepository::class)]
#[Vich\Uploadable]
class Outfit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de la tenue ne peut pas être vide")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom doit faire au moins {{ limit }} caractères",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "L'occasion ne peut pas être vide")]
    #[Assert\Length(
        max: 100,
        maxMessage: "L'occasion ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $occasion = null;

    #[ORM\Column(enumType: WardrobeSeason::class)]
    #[Assert\NotNull(message: "La saison doit être spécifiée")]
    private ?WardrobeSeason $season = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'outfits', fileNameProperty: 'image')]
    #[Assert\Image(
        maxSize: "5M",
        mimeTypes: ["image/jpeg", "image/png"],
        maxSizeMessage: "L'image ne doit pas dépasser 5 Mo",
        mimeTypesMessage: "Formats acceptés : JPEG, PNG"
    )]
    private ?File $imageFile = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'outfits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "L'utilisateur doit être spécifié")]
    private ?User $customer = null;

    /**
     * @var Collection<int, OutfitItem>
     */
    #[ORM\OneToMany(targetEntity: OutfitItem::class, mappedBy: 'outfit', cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $outfitItems;

    #[ORM\Column]
    private ?bool $isPublic = null;

    /**
     * @var Collection<int, AiAnalysis>
     */
    #[ORM\OneToMany(targetEntity: AiAnalysis::class, mappedBy: 'OutfitId')]
    private Collection $OutfitId;

    public function __construct()
    {
        $this->outfitItems = new ArrayCollection();
        $this->OutfitId = new ArrayCollection();
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

    public function getOccasion(): ?string
    {
        return $this->occasion;
    }

    public function setOccasion(string $occasion): static
    {
        $this->occasion = $occasion;

        return $this;
    }

    public function getSeason(): ?WardrobeSeason
    {
        return $this->season;
    }

    public function setSeason(WardrobeSeason $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
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
            $outfitItem->setOutfit($this);
        }

        return $this;
    }

    public function removeOutfitItem(OutfitItem $outfitItem): static
    {
        if ($this->outfitItems->removeElement($outfitItem)) {
            // set the owning side to null (unless already changed)
            if ($outfitItem->getOutfit() === $this) {
                $outfitItem->setOutfit(null);
            }
        }

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection<int, AiAnalysis>
     */
    public function getOutfitId(): Collection
    {
        return $this->OutfitId;
    }

    public function addOutfitId(AiAnalysis $outfitId): static
    {
        if (!$this->OutfitId->contains($outfitId)) {
            $this->OutfitId->add($outfitId);
            $outfitId->setOutfitId($this);
        }

        return $this;
    }

    public function removeOutfitId(AiAnalysis $outfitId): static
    {
        if ($this->OutfitId->removeElement($outfitId)) {
            if ($outfitId->getOutfitId() === $this) {
                $outfitId->setOutfitId(null);
            }
        }

        return $this;
    }
}
