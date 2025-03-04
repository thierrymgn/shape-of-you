<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'customer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Profile $profile = null;

    /**
     * @var Collection<int, WardrobeItem>
     */
    #[ORM\OneToMany(targetEntity: WardrobeItem::class, mappedBy: 'customer')]
    private Collection $wardrobeItems;

    /**
     * @var Collection<int, Outfit>
     */
    #[ORM\OneToMany(targetEntity: Outfit::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $outfits;

    /**
     * @var Collection<int, Follow>
     */
    #[ORM\OneToMany(targetEntity: Follow::class, mappedBy: 'follower')]
    private Collection $following;

    /**
     * @var Collection<int, Follow>
     */
    #[ORM\OneToMany(targetEntity: Follow::class, mappedBy: 'following')]
    private Collection $followers;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'userId')]
    private Collection $comments;

    /**
     * @var Collection<int, PartnerOrder>
     */
    #[ORM\OneToMany(targetEntity: PartnerOrder::class, mappedBy: 'userId')]
    private Collection $partnerOrders;

    /**
     * @var Collection<int, CommentLike>
     */
    #[ORM\OneToMany(targetEntity: CommentLike::class, mappedBy: 'userId')]
    private Collection $commentLikes;

    public function __construct()
    {
        $this->wardrobeItems = new ArrayCollection();
        $this->outfits = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->partnerOrders = new ArrayCollection();
        $this->commentLikes = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection<int, WardrobeItem>
     */
    public function getWardrobeItems(): Collection
    {
        return $this->wardrobeItems;
    }

    public function addWardrobeItem(WardrobeItem $wardrobeItem): static
    {
        if (!$this->wardrobeItems->contains($wardrobeItem)) {
            $this->wardrobeItems->add($wardrobeItem);
            $wardrobeItem->setCustomer($this);
        }

        return $this;
    }

    public function removeWardrobeItem(WardrobeItem $wardrobeItem): static
    {
        if ($this->wardrobeItems->removeElement($wardrobeItem)) {
            // set the owning side to null (unless already changed)
            if ($wardrobeItem->getCustomer() === $this) {
                $wardrobeItem->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Outfit>
     */
    public function getOutfits(): Collection
    {
        return $this->outfits;
    }

    public function addOutfit(Outfit $outfit): static
    {
        if (!$this->outfits->contains($outfit)) {
            $this->outfits->add($outfit);
            $outfit->setCustomer($this);
        }

        return $this;
    }

    public function removeOutfit(Outfit $outfit): static
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
     * @return Collection<int, Follow>
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function addFollowing(Follow $following): static
    {
        if (!$this->following->contains($following)) {
            $this->following->add($following);
            $following->setFollower($this);
        }

        return $this;
    }

    public function removeFollowing(Follow $following): void
    {
        if ($this->following->removeElement($following)) {
            // set the owning side to null (unless already changed)
            if ($following->getFollower() === $this) {
                $following->setFollower(null);
            }
        }
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): static
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return Collection<int, Follow>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Follow $follower): void
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
            $follower->setFollowing($this);
        }
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUserId($this);
        }

        return $this;
    }

    public function removeFollower(Follow $follower): void
    {
        if ($this->followers->removeElement($follower)) {
            // set the owning side to null (unless already changed)
            if ($follower->getFollowing() === $this) {
                $follower->setFollowing(null);
            }
        }
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUserId() === $this) {
                $comment->setUserId(null);
            }
        }

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
            $partnerOrder->setUserId($this);
        }

        return $this;
    }

    public function removePartnerOrder(PartnerOrder $partnerOrder): static
    {
        if ($this->partnerOrders->removeElement($partnerOrder)) {
            // set the owning side to null (unless already changed)
            if ($partnerOrder->getUserId() === $this) {
                $partnerOrder->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentLike>
     */
    public function getCommentLikes(): Collection
    {
        return $this->commentLikes;
    }

    public function addCommentLike(CommentLike $commentLike): static
    {
        if (!$this->commentLikes->contains($commentLike)) {
            $this->commentLikes->add($commentLike);
            $commentLike->setUserId($this);
        }

        return $this;
    }

    public function removeCommentLike(CommentLike $commentLike): static
    {
        if ($this->commentLikes->removeElement($commentLike)) {
            // set the owning side to null (unless already changed)
            if ($commentLike->getUserId() === $this) {
                $commentLike->setUserId(null);
            }
        }

        return $this;
    }
}
