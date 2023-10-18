<?php

namespace App\Entity;

use App\Repository\StuffDropRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StuffDropRepository::class)]
class StuffDrop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stuffDrops', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['resource:drops', 'mob:drops'])]
    private ?Stuff $stuff = null;

    #[ORM\ManyToOne(inversedBy: 'stuffDrops', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('stuff:drops')]
    private ?Mobs $mob = null;

    #[ORM\Column]
    #[Groups(['resource:drops', 'mob:drops', 'stuff:drops'])]
    private ?float $value = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMob(): ?Mobs
    {
        return $this->mob;
    }

    public function setMob(?Mobs $mob): static
    {
        $this->mob = $mob;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }
}
