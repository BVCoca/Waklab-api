<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StuffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StuffRepository::class)]
class Stuff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['mob:drops', 'recipeIngredients'])]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type : "string", length : 128, unique : false, nullable : true)]
    #[Groups(['mob:drops', 'recipeIngredients'])]
    private ?string $slug = null;

    #[ORM\Column]
    #[Groups(['mob:drops', 'recipeIngredients'])]
    private ?int $level = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['mob:drops', 'recipeIngredients'])]
    private ?string $imageUrl = null;

    #[ORM\ManyToOne(inversedBy: 'stuffs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['mob:drops', 'recipeIngredients'])]
    private ?Rarity $rarity = null;

    #[ORM\ManyToOne(inversedBy: 'stuffs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('typeStuff')]
    private ?TypeStuff $type = null;

    #[ORM\OneToMany(mappedBy: 'stuff', targetEntity: StuffCaracteristic::class, cascade: ['persist'])]
    private Collection $stuffCaracteristics;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zoneType = null;

    #[ORM\Column(nullable: true)]
    private ?int $costPa = null;

    #[ORM\Column(nullable: true)]
    private ?int $requiredPo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $effectType = null;

    #[ORM\Column(nullable: true)]
    private ?int $effectValue = null;

    #[ORM\Column(nullable: true)]
    private ?int $criticalEffectValue = null;

    #[ORM\OneToMany(mappedBy: 'stuff', targetEntity: StuffDrop::class, orphanRemoval: true)]
    private ?Collection $stuffDrops;

    #[ORM\OneToMany(mappedBy: 'stuff', targetEntity: Recipe::class)]
    private ?Collection $recipes;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: RecipeIngredient::class, cascade: ['persist'])]
    private ?Collection $recipeIngredients;

    public function __construct()
    {
        $this->stuffCaracteristics = new ArrayCollection();
        $this->stuffDrops = new ArrayCollection();
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

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

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

    public function getType(): ?TypeStuff
    {
        return $this->type;
    }

    public function setType(?TypeStuff $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, StuffCaracteristic>
     */
    public function getStuffCaracteristics(): Collection
    {
        return $this->stuffCaracteristics;
    }

    public function addStuffCaracteristic(StuffCaracteristic $stuffCaracteristic): static
    {
        if (!$this->stuffCaracteristics->contains($stuffCaracteristic)) {
            $this->stuffCaracteristics->add($stuffCaracteristic);
            $stuffCaracteristic->setStuff($this);
        }

        return $this;
    }

    public function removeStuffCaracteristic(StuffCaracteristic $stuffCaracteristic): static
    {
        if ($this->stuffCaracteristics->removeElement($stuffCaracteristic)) {
            // set the owning side to null (unless already changed)
            if ($stuffCaracteristic->getStuff() === $this) {
                $stuffCaracteristic->setStuff(null);
            }
        }

        return $this;
    }

    public function getZoneType(): ?string
    {
        return $this->zoneType;
    }

    public function setZoneType(?string $zoneType): static
    {
        $this->zoneType = $zoneType;

        return $this;
    }

    public function getCostPa(): ?int
    {
        return $this->costPa;
    }

    public function setCostPa(?int $costPa): static
    {
        $this->costPa = $costPa;

        return $this;
    }

    public function getRequiredPo(): ?int
    {
        return $this->requiredPo;
    }

    public function setRequiredPo(?int $requiredPo): static
    {
        $this->requiredPo = $requiredPo;

        return $this;
    }

    public function getEffectType(): ?string
    {
        return $this->effectType;
    }

    public function setEffectType(?string $effectType): static
    {
        $this->effectType = $effectType;

        return $this;
    }

    public function getEffectValue(): ?int
    {
        return $this->effectValue;
    }

    public function setEffectValue(?int $effectValue): static
    {
        $this->effectValue = $effectValue;

        return $this;
    }

    public function getCriticalEffectValue(): ?int
    {
        return $this->criticalEffectValue;
    }

    public function setCriticalEffectValue(?int $criticalEffectValue): static
    {
        $this->criticalEffectValue = $criticalEffectValue;

        return $this;
    }

    /**
     * @return Collection<int, StuffDrop>
     */
    public function getStuffDrops(): ?Collection
    {
        return $this->stuffDrops;
    }

    public function setStuffDrops($value): static
    {
        $this->stuffDrops = $value;

        return $this;
    }

    public function addStuffDrop(StuffDrop $stuffDrop): static
    {
        if (!$this->stuffDrops->contains($stuffDrop)) {
            $this->stuffDrops->add($stuffDrop);
            $stuffDrop->setStuff($this);
        }

        return $this;
    }

    public function removeStuffDrop(StuffDrop $stuffDrop): static
    {
        if ($this->stuffDrops->removeElement($stuffDrop)) {
            // set the owning side to null (unless already changed)
            if ($stuffDrop->getStuff() === $this) {
                $stuffDrop->setStuff(null);
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
            $recipe->setStuff($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getStuff() === $this) {
                $recipe->setStuff(null);
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
            $recipeIngredient->setStuff($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getStuff() === $this) {
                $recipeIngredient->setStuff(null);
            }
        }

        return $this;
    }
}
