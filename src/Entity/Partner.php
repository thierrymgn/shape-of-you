<?php

namespace App\Entity;

use App\Enum\PartnerStatus;
use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $websiteUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiKey = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiSecret = null;

    #[ORM\Column(enumType: PartnerStatus::class)]
    private ?PartnerStatus $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $commissionRate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, PartnerOrder>
     */
    #[ORM\OneToMany(targetEntity: PartnerOrder::class, mappedBy: 'partnerId')]
    private Collection $partnerOrders;

    public function __construct()
    {
        $this->partnerOrders = new ArrayCollection();
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

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(string $websiteUrl): static
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getApiSecret(): ?string
    {
        return $this->apiSecret;
    }

    public function setApiSecret(?string $apiSecret): static
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    public function getStatus(): ?PartnerStatus
    {
        return $this->status;
    }

    public function setStatus(PartnerStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCommissionRate(): ?string
    {
        return $this->commissionRate;
    }

    public function setCommissionRate(?string $commissionRate): static
    {
        $this->commissionRate = $commissionRate;

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
     * @return Collection<int, PartnerOrder>
     */
    public function getPartnerOrders(): Collection
    {
        return $this->partnerOrders;
    }

    public function addPartnerOrder(PartnerOrder $partnerOrder): static
    {
        if (!$this->partnerOrders->contains($partnerOrder)) {
            $this->partnerOrders->add($partnerOrder);
            $partnerOrder->setPartnerId($this);
        }

        return $this;
    }

    public function removePartnerOrder(PartnerOrder $partnerOrder): static
    {
        if ($this->partnerOrders->removeElement($partnerOrder)) {
            // set the owning side to null (unless already changed)
            if ($partnerOrder->getPartnerId() === $this) {
                $partnerOrder->setPartnerId(null);
            }
        }

        return $this;
    }
}
