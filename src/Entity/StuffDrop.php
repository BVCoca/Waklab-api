<?php

namespace App\Entity;

use App\Repository\StuffDropRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StuffDropRepository::class)]
class StuffDrop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stuffDrops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stuff $stuff = null;

    #[ORM\ManyToOne(inversedBy: 'stuffDrops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mobs $mob = null;

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
}
