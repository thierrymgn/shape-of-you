<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $height = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $bodyType = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    private array $stylePreferences = [];

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    private array $colorPreferences = [];

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    private array $sizePreferences = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?User $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(?string $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getBodyType(): ?string
    {
        return $this->bodyType;
    }

    public function setBodyType(?string $bodyType): static
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getStylePreferences(): array
    {
        return $this->stylePreferences;
    }

    /**
     * @param array<string, string> $stylePreferences
     *
     * @return $this
     */
    public function setStylePreferences(array $stylePreferences): static
    {
        $this->stylePreferences = $stylePreferences;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getColorPreferences(): array
    {
        return $this->colorPreferences;
    }

    /**
     * @param array<string, string> $colorPreferences
     *
     * @return $this
     */
    public function setColorPreferences(array $colorPreferences): static
    {
        $this->colorPreferences = $colorPreferences;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getSizePreferences(): array
    {
        return $this->sizePreferences;
    }

    /**
     * @param array<string, string> $sizePreferences
     *
     * @return $this
     */
    public function setSizePreferences(array $sizePreferences): static
    {
        $this->sizePreferences = $sizePreferences;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(User $customer): static
    {
        // set the owning side of the relation if necessary
        if ($customer->getProfile() !== $this) {
            $customer->setProfile($this);
        }

        $this->customer = $customer;

        return $this;
    }
}
