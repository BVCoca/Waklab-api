<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StuffRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StuffRepository::class)]
#[ApiResource]
class Stuff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $effect = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $criticalEffect = null;

    #[ORM\ManyToOne(inversedBy: 'stuffs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?rarity $rarity = null;

    #[ORM\Column]
    private ?int $costPa = null;

    #[ORM\Column]
    private ?int $rangeNeeded = null;

    #[ORM\Column(nullable: true)]
    private ?int $actionPoint = null;

    #[ORM\Column(nullable: true)]
    private ?int $movementPoint = null;

    #[ORM\Column(nullable: true)]
    private ?int $wakfuPoint = null;

    #[ORM\Column(nullable: true)]
    private ?int $healthPoint = null;

    #[ORM\Column(nullable: true)]
    private ?int $attackWater = null;

    #[ORM\Column(nullable: true)]
    private ?int $resWater = null;

    #[ORM\Column(nullable: true)]
    private ?int $resEarth = null;

    #[ORM\Column(nullable: true)]
    private ?int $attackEarth = null;

    #[ORM\Column(nullable: true)]
    private ?int $resWind = null;

    #[ORM\Column(nullable: true)]
    private ?int $attackWind = null;

    #[ORM\Column(nullable: true)]
    private ?int $resFire = null;

    #[ORM\Column(nullable: true)]
    private ?int $attackFire = null;

    #[ORM\Column(nullable: true)]
    private ?int $inflictedDamage = null;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function setEffect(?string $effect): static
    {
        $this->effect = $effect;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getCriticalEffect(): ?string
    {
        return $this->criticalEffect;
    }

    public function setCriticalEffect(?string $criticalEffect): static
    {
        $this->criticalEffect = $criticalEffect;

        return $this;
    }

    public function getRarity(): ?rarity
    {
        return $this->rarity;
    }

    public function setRarity(?rarity $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getCostPa(): ?int
    {
        return $this->costPa;
    }

    public function setCostPa(int $costPa): static
    {
        $this->costPa = $costPa;

        return $this;
    }

    public function getRangeNeeded(): ?int
    {
        return $this->rangeNeeded;
    }

    public function setRangeNeeded(int $rangeNeeded): static
    {
        $this->rangeNeeded = $rangeNeeded;

        return $this;
    }

    public function getActionPoint(): ?int
    {
        return $this->actionPoint;
    }

    public function setActionPoint(?int $actionPoint): static
    {
        $this->actionPoint = $actionPoint;

        return $this;
    }

    public function getMovementPoint(): ?int
    {
        return $this->movementPoint;
    }

    public function setMovementPoint(?int $movementPoint): static
    {
        $this->movementPoint = $movementPoint;

        return $this;
    }

    public function getWakfuPoint(): ?int
    {
        return $this->wakfuPoint;
    }

    public function setWakfuPoint(?int $wakfuPoint): static
    {
        $this->wakfuPoint = $wakfuPoint;

        return $this;
    }

    public function getHealthPoint(): ?int
    {
        return $this->healthPoint;
    }

    public function setHealthPoint(?int $healthPoint): static
    {
        $this->healthPoint = $healthPoint;

        return $this;
    }

    public function getAttackWater(): ?int
    {
        return $this->attackWater;
    }

    public function setAttackWater(?int $attackWater): static
    {
        $this->attackWater = $attackWater;

        return $this;
    }

    public function getResWater(): ?int
    {
        return $this->resWater;
    }

    public function setResWater(?int $resWater): static
    {
        $this->resWater = $resWater;

        return $this;
    }

    public function getResEarth(): ?int
    {
        return $this->resEarth;
    }

    public function setResEarth(?int $resEarth): static
    {
        $this->resEarth = $resEarth;

        return $this;
    }

    public function getAttackEarth(): ?int
    {
        return $this->attackEarth;
    }

    public function setAttackEarth(?int $attackEarth): static
    {
        $this->attackEarth = $attackEarth;

        return $this;
    }

    public function getResWind(): ?int
    {
        return $this->resWind;
    }

    public function setResWind(?int $resWind): static
    {
        $this->resWind = $resWind;

        return $this;
    }

    public function getAttackWind(): ?int
    {
        return $this->attackWind;
    }

    public function setAttackWind(?int $attackWind): static
    {
        $this->attackWind = $attackWind;

        return $this;
    }

    public function getResFire(): ?int
    {
        return $this->resFire;
    }

    public function setResFire(?int $resFire): static
    {
        $this->resFire = $resFire;

        return $this;
    }

    public function getAttackFire(): ?int
    {
        return $this->attackFire;
    }

    public function setAttackFire(?int $attackFire): static
    {
        $this->attackFire = $attackFire;

        return $this;
    }

    public function getInflictedDamage(): ?int
    {
        return $this->inflictedDamage;
    }

    public function setInflictedDamage(?int $inflictedDamage): static
    {
        $this->inflictedDamage = $inflictedDamage;

        return $this;
    }
}
