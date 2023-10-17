<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ApiResource(operations: [
    new Get(
        normalizationContext:['groups' => ['resource:item', 'resource:drops', 'rarity', 'recipes', 'recipeIngredients']],
    )
])]
class Resource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:item', 'mob:drops'])]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type : "string", length : 128, unique : false, nullable : true)]
    #[Groups(['resource:item', 'mob:drops'])]
    #[ApiProperty(identifier: true)]
    private ?string $slug = null;

    #[ORM\Column]
    #[Groups(['resource:item', 'mob:drops'])]
    private ?int $level = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['resource:item'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('rarity')]
    private ?Rarity $rarity = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:item', 'mob:drops'])]
    private ?string $imageUrl = null;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: ResourceDrop::class, orphanRemoval: true, cascade: ['persist'])]
    #[Groups('resource:drops')]
    private ?Collection $resourceDrops;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Recipe::class, cascade: ['persist'])]
    #[Groups('recipes')]
    private ?Collection $recipes;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: RecipeIngredient::class, cascade: ['persist'])]
    #[Groups('recipeIngredients')]
    private ?Collection $recipeIngredients;

    public function __construct()
    {
        $this->resourceDrops = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug($slug): self
    {
        $this->slug = $slug;

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
    public function getResourceDrops(): ?Collection
    {
        return $this->resourceDrops;
    }

    public function setResourceDrops($value): static
    {
        $this->resourceDrops = $value;

        return $this;
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
    public function getRecipes(): ?Collection
    {
        return $this->recipes;
    }

    public function setRecipes($value): static
    {
        $this->recipes = $value;

        return $this;
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

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipeIngredients(): ?Collection
    {
        return $this->recipeIngredients;
    }

    public function setRecipeIngredients($value): static
    {
        $this->recipeIngredients = $value;

        return $this;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setResource($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getResource() === $this) {
                $recipeIngredient->setResource(null);
            }
        }

        return $this;
    }
}
