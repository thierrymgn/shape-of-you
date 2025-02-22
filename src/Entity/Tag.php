<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, WardrobeItemTag>
     */
    #[ORM\OneToMany(targetEntity: WardrobeItemTag::class, mappedBy: 'tag', orphanRemoval: true)]
    private Collection $wardrobeItemTags;

    public function __construct()
    {
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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
            $wardrobeItemTag->setTag($this);
        }

        return $this;
    }

    public function removeWardrobeItemTag(WardrobeItemTag $wardrobeItemTag): static
    {
        if ($this->wardrobeItemTags->removeElement($wardrobeItemTag)) {
            // set the owning side to null (unless already changed)
            if ($wardrobeItemTag->getTag() === $this) {
                $wardrobeItemTag->setTag(null);
            }
        }

        return $this;
    }
}
