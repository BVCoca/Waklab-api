<?php

namespace App\Entity;

use App\Repository\StuffCaracteristicRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StuffCaracteristicRepository::class)]
class StuffCaracteristic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stuffCaracteristics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stuff $stuff = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Caracteristic $caracteristic = null;

    #[ORM\Column]
    private ?int $value = null;

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

    public function getCaracteristic(): ?Caracteristic
    {
        return $this->caracteristic;
    }

    public function setCaracteristic(?Caracteristic $caracteristic): static
    {
        $this->caracteristic = $caracteristic;

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
