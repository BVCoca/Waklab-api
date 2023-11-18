<?php

namespace App\Entity;

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
    #[Groups('family')]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type : 'string', length : 128, unique : false, nullable : true)]
    #[Groups('family')]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'family', targetEntity: Mobs::class, orphanRemoval: true)]
    #[Groups('subzone:item')]
    private Collection $Mobs;

    #[ORM\ManyToMany(targetEntity: Subzone::class, mappedBy: 'mobs')]
    #[Groups('subzone')]
    private Collection $subzones;

    public function __construct()
    {
        $this->Mobs = new ArrayCollection();
        $this->subzones = new ArrayCollection();
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

    /**
     * @return Collection<int, Subzone>
     */
    public function getSubzones(): Collection
    {
        return $this->subzones;
    }

    public function addSubzone(Subzone $subzone): static
    {
        if (!$this->subzones->contains($subzone)) {
            $this->subzones->add($subzone);
            $subzone->addMob($this);
        }

        return $this;
    }

    public function removeSubzone(Subzone $subzone): static
    {
        if ($this->subzones->removeElement($subzone)) {
            $subzone->removeMob($this);
        }

        return $this;
    }
}
