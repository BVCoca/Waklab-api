<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ApiResource]
class Resource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rarity $rarity = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\OneToMany(mappedBy: 'Resource', targetEntity: ResourceDrop::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $resourceDrops;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Recipe::class, cascade: ['persist'])]
    private Collection $recipes;

    public function __construct()
    {
        $this->resourceDrops = new ArrayCollection();
        $this->recipes = new ArrayCollection();
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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

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

    public function getRarity(): ?Rarity
    {
        return $this->rarity;
    }

    public function setRarity(?Rarity $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
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

    /**
     * @return Collection<int, ResourceDrop>
     */
    public function getResourceDrops(): Collection
    {
        return $this->resourceDrops;
    }

    public function addResourceDrop(ResourceDrop $resourceDrop): static
    {
        if (!$this->resourceDrops->contains($resourceDrop)) {
            $this->resourceDrops->add($resourceDrop);
            $resourceDrop->setResource($this);
        }

        return $this;
    }

    public function removeResourceDrop(ResourceDrop $resourceDrop): static
    {
        if ($this->resourceDrops->removeElement($resourceDrop)) {
            // set the owning side to null (unless already changed)
            if ($resourceDrop->getResource() === $this) {
                $resourceDrop->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setResource($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getResource() === $this) {
                $recipe->setResource(null);
            }
        }

        return $this;
    }
}
