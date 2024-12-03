<?php

namespace App\Entity;

use App\Enum\UserRolesEnum;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(enumType: UserRolesEnum::class)]
    private ?UserRolesEnum $role = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Outfits>
     */
    #[ORM\OneToMany(targetEntity: Outfits::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $outfits;

    /**
     * @var Collection<int, OutfitHistory>
     */
    #[ORM\OneToMany(targetEntity: OutfitHistory::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $outfitHistories;

    /**
     * @var Collection<int, SocialPosts>
     */
    #[ORM\OneToMany(targetEntity: SocialPosts::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $socialPosts;

    /**
     * @var Collection<int, AIAlerts>
     */
    #[ORM\OneToMany(targetEntity: AIAlerts::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $aIAlerts;

    /**
     * @var Collection<int, VoiceCommands>
     */
    #[ORM\OneToMany(targetEntity: VoiceCommands::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $voiceCommands;

    /**
     * @var Collection<int, ModeratorActions>
     */
    #[ORM\OneToMany(targetEntity: ModeratorActions::class, mappedBy: 'moderator', orphanRemoval: true)]
    private Collection $moderatorActions;

    public function __construct()
    {
        $this->outfits = new ArrayCollection();
        $this->outfitHistories = new ArrayCollection();
        $this->socialPosts = new ArrayCollection();
        $this->aIAlerts = new ArrayCollection();
        $this->voiceCommands = new ArrayCollection();
        $this->moderatorActions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?UserRolesEnum
    {
        return $this->role;
    }

    public function setRole(UserRolesEnum $role): static
    {
        $this->role = $role;

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
     * @return Collection<int, Outfits>
     */
    public function getOutfits(): Collection
    {
        return $this->outfits;
    }

    public function addOutfit(Outfits $outfit): static
    {
        if (!$this->outfits->contains($outfit)) {
            $this->outfits->add($outfit);
            $outfit->setCustomer($this);
        }

        return $this;
    }

    public function removeOutfit(Outfits $outfit): static
    {
        if ($this->outfits->removeElement($outfit)) {
            // set the owning side to null (unless already changed)
            if ($outfit->getCustomer() === $this) {
                $outfit->setCustomer(null);
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
            $outfitHistory->setCustomer($this);
        }

        return $this;
    }

    public function removeOutfitHistory(OutfitHistory $outfitHistory): static
    {
        if ($this->outfitHistories->removeElement($outfitHistory)) {
            // set the owning side to null (unless already changed)
            if ($outfitHistory->getCustomer() === $this) {
                $outfitHistory->setCustomer(null);
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
            $socialPost->setCustomer($this);
        }

        return $this;
    }

    public function removeSocialPost(SocialPosts $socialPost): static
    {
        if ($this->socialPosts->removeElement($socialPost)) {
            // set the owning side to null (unless already changed)
            if ($socialPost->getCustomer() === $this) {
                $socialPost->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AIAlerts>
     */
    public function getAIAlerts(): Collection
    {
        return $this->aIAlerts;
    }

    public function addAIAlert(AIAlerts $aIAlert): static
    {
        if (!$this->aIAlerts->contains($aIAlert)) {
            $this->aIAlerts->add($aIAlert);
            $aIAlert->setCustomer($this);
        }

        return $this;
    }

    public function removeAIAlert(AIAlerts $aIAlert): static
    {
        if ($this->aIAlerts->removeElement($aIAlert)) {
            // set the owning side to null (unless already changed)
            if ($aIAlert->getCustomer() === $this) {
                $aIAlert->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VoiceCommands>
     */
    public function getVoiceCommands(): Collection
    {
        return $this->voiceCommands;
    }

    public function addVoiceCommand(VoiceCommands $voiceCommand): static
    {
        if (!$this->voiceCommands->contains($voiceCommand)) {
            $this->voiceCommands->add($voiceCommand);
            $voiceCommand->setCustomer($this);
        }

        return $this;
    }

    public function removeVoiceCommand(VoiceCommands $voiceCommand): static
    {
        if ($this->voiceCommands->removeElement($voiceCommand)) {
            // set the owning side to null (unless already changed)
            if ($voiceCommand->getCustomer() === $this) {
                $voiceCommand->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModeratorActions>
     */
    public function getModeratorActions(): Collection
    {
        return $this->moderatorActions;
    }

    public function addModeratorAction(ModeratorActions $moderatorAction): static
    {
        if (!$this->moderatorActions->contains($moderatorAction)) {
            $this->moderatorActions->add($moderatorAction);
            $moderatorAction->setModerator($this);
        }

        return $this;
    }

    public function removeModeratorAction(ModeratorActions $moderatorAction): static
    {
        if ($this->moderatorActions->removeElement($moderatorAction)) {
            // set the owning side to null (unless already changed)
            if ($moderatorAction->getModerator() === $this) {
                $moderatorAction->setModerator(null);
            }
        }

        return $this;
    }
}
