<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FamilyRepository::class)]
class Family
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('drops')]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type : "string", length : 128, unique : false, nullable : true)]
    #[Groups('drops')]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'family', targetEntity: Mobs::class, orphanRemoval: true)]
    private Collection $Mobs;

    public function __construct()
    {
        $this->Mobs = new ArrayCollection();
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

    /**
     * @return Collection<int, Mobs>
     */
    public function getMobs(): Collection
    {
        return $this->Mobs;
    }

    public function addMobs(Mobs $mobs): static
    {
        if (!$this->Mobs->contains($mobs)) {
            $this->Mobs->add($mobs);
            $mobs->setFamily($this);
        }

        return $this;
    }

    public function removeMobs(Mobs $mobs): static
    {
        if ($this->Mobs->removeElement($mobs)) {
            // set the owning side to null (unless already changed)
            if ($mobs->getFamily() === $this) {
                $mobs->setFamily(null);
            }
        }

        return $this;
    }
}
