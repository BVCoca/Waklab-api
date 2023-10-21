<?php

namespace App\Entity;

use App\Repository\ResourceDropRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResourceDropRepository::class)]
class ResourceDrop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'resourceDrops', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['resource:drops', 'mob:drops'])]
    private ?Resource $resource = null;

    #[ORM\ManyToOne(inversedBy: 'resourceDrops', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('resource:drops')]
    private ?Mobs $mob = null;

    #[ORM\Column]
    #[Groups(['resource:drops', 'mob:drops'])]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $Resource): static
    {
        $this->resource = $Resource;

        return $this;
    }

    public function getMob(): ?Mobs
    {
        return $this->mob;
    }

    public function setMob(?Mobs $Mob): static
    {
        $this->mob = $Mob;

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
