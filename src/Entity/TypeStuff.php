<?php

namespace App\Entity;

use App\Repository\TypeStuffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeStuffRepository::class)]
class TypeStuff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Stuff::class)]
    private Collection $stuffs;

    public function __construct()
    {
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
            $stuff->setType($this);
        }

        return $this;
    }

    public function removeStuff(Stuff $stuff): static
    {
        if ($this->stuffs->removeElement($stuff)) {
            // set the owning side to null (unless already changed)
            if ($stuff->getType() === $this) {
                $stuff->setType(null);
            }
        }

        return $this;
    }
}
