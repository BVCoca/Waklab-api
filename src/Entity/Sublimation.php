<?php

namespace App\Entity;

use App\Repository\SublimationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SublimationRepository::class)]
class Sublimation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['resource:item'])]
    private ?string $effect = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:item'])]
    private ?string $first_chasse = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:item'])]
    private ?string $second_chasse = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:item'])]
    private ?string $third_chasse = null;

    #[ORM\OneToMany(mappedBy: 'sublimation', targetEntity: Resource::class)]
    private Collection $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
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

    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function setEffect(string $effect): static
    {
        $this->effect = $effect;

        return $this;
    }

    public function getFirstChasse(): ?string
    {
        return $this->first_chasse;
    }

    public function setFirstChasse(string $first_chasse): static
    {
        $this->first_chasse = $first_chasse;

        return $this;
    }

    public function getSecondChasse(): ?string
    {
        return $this->second_chasse;
    }

    public function setSecondChasse(string $second_chasse): static
    {
        $this->second_chasse = $second_chasse;

        return $this;
    }

    public function getThirdChasse(): ?string
    {
        return $this->third_chasse;
    }

    public function setThirdChasse(string $third_chasse): static
    {
        $this->third_chasse = $third_chasse;

        return $this;
    }

    /**
     * @return Collection<int, resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
            $resource->setSublimation($this);
        }

        return $this;
    }

    public function removeresource(Resource $resources): static
    {
        if ($this->resources->removeElement($resources)) {
            // set the owning side to null (unless already changed)
            if ($resources->getSublimation() === $this) {
                $resources->setSublimation(null);
            }
        }

        return $this;
    }
}
