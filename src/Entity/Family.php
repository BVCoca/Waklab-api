<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilyRepository::class)]
#[ApiResource]
class Family
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'family', targetEntity: Mobs::class, orphanRemoval: true)]
    private Collection $Mob;

    public function __construct()
    {
        $this->Mob = new ArrayCollection();
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

    /**
     * @return Collection<int, Mob>
     */
    public function getMob(): Collection
    {
        return $this->Mob;
    }

    public function addMob(Mobs $mob): static
    {
        if (!$this->Mob->contains($mob)) {
            $this->Mob->add($mob);
            $mob->setFamily($this);
        }

        return $this;
    }

    public function removeMob(Mobs $mob): static
    {
        if ($this->Mob->removeElement($mob)) {
            // set the owning side to null (unless already changed)
            if ($mob->getFamily() === $this) {
                $mob->setFamily(null);
            }
        }

        return $this;
    }
}