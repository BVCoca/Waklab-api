<?php

namespace App\Entity;

use App\Repository\ResourceDropRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResourceDropRepository::class)]
class ResourceDrop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'resourceDrops', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resource $Resource = null;

    #[ORM\ManyToOne(inversedBy: 'resourceDrops', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mobs $Mob = null;

    #[ORM\Column]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResource(): ?Resource
    {
        return $this->Resource;
    }

    public function setResource(?Resource $Resource): static
    {
        $this->Resource = $Resource;

        return $this;
    }

    public function getMob(): ?Mobs
    {
        return $this->Mob;
    }

    public function setMob(?Mobs $Mob): static
    {
        $this->Mob = $Mob;

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
}
