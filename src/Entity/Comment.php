<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column]
    private ?int $repliesCount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?SocialPost $postId = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comments')]
    private ?self $commentId = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'commentId')]
    private Collection $comments;

    /**
     * @var Collection<int, CommentLike>
     */
    #[ORM\OneToMany(targetEntity: CommentLike::class, mappedBy: 'commentID')]
    private Collection $commentLikes;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
        $this->commentLikes = new ArrayCollection();
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getRepliesCount(): ?int
    {
        return $this->repliesCount;
    }

    public function setRepliesCount(int $repliesCount): static
    {
        $this->repliesCount = $repliesCount;

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

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getPostId(): ?SocialPost
    {
        return $this->postId;
    }

    public function setPostId(?SocialPost $postId): static
    {
        $this->postId = $postId;

        return $this;
    }

    public function getCommentId(): ?self
    {
        return $this->commentId;
    }

    public function setCommentId(?self $commentId): static
    {
        $this->commentId = $commentId;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setCommentId($this);
        }

        return $this;
    }

    public function removeComment(self $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCommentId() === $this) {
                $comment->setCommentId(null);
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
            $commentLike->setCommentID($this);
        }

        return $this;
    }

    public function removeCommentLike(CommentLike $commentLike): static
    {
        if ($this->commentLikes->removeElement($commentLike)) {
            // set the owning side to null (unless already changed)
            if ($commentLike->getCommentID() === $this) {
                $commentLike->setCommentID(null);
            }
        }

        return $this;
    }
}
