<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RarityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RarityRepository::class)]
class Rarity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('rarity')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('rarity')]
    private ?string $icon = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\OneToMany(mappedBy: 'rarity', targetEntity: Resource::class)]
    private Collection $resources;

    #[ORM\OneToMany(mappedBy: 'rarity', targetEntity: Stuff::class)]
    private Collection $stuffs;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->stuffs = new ArrayCollection();
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
            $resource->setRarity($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): static
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getRarity() === $this) {
                $resource->setRarity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stuff>
     */
    public function getStuffs(): Collection
    {
        return $this->stuffs;
    }

    public function addStuff(Stuff $stuff): static
    {
        if (!$this->stuffs->contains($stuff)) {
            $this->stuffs->add($stuff);
            $stuff->setRarity($this);
        }

        return $this;
    }

    public function removeStuff(Stuff $stuff): static
    {
        if ($this->stuffs->removeElement($stuff)) {
            // set the owning side to null (unless already changed)
            if ($stuff->getRarity() === $this) {
                $stuff->setRarity(null);
            }
        }

        return $this;
    }
}
