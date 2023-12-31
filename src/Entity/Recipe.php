<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[Groups('job')]
    private ?Job $job = null;

    #[ORM\Column(nullable: true)]
    #[Groups('job')]
    private ?int $job_level = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class, orphanRemoval: true, cascade: ['persist'])]
    #[Groups('recipes')]
    private ?Collection $recipeIngredients;

    #[ORM\ManyToOne(inversedBy: 'recipes', cascade: ['persist'])]
    #[Groups('recipeIngredients')]
    private ?Resource $resource = null;

    #[ORM\ManyToOne(inversedBy: 'recipes', cascade: ['persist'])]
    #[Groups('recipeIngredients')]
    private ?Stuff $stuff = null;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getJobLevel(): ?int
    {
        return $this->job_level;
    }

    public function setJobLevel(?int $job_level): static
    {
        $this->job_level = $job_level;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
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
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function getStuff(): ?Stuff
    {
        return $this->stuff;
    }

    public function setStuff(?Stuff $stuff): static
    {
        $this->stuff = $stuff;

        return $this;
    }
}
