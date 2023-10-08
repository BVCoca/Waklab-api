<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MobsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MobsRepository::class)]
#[ApiResource]
class Mobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $actionPoints = null;

    #[ORM\Column]
    private ?int $movementPoints = null;

    #[ORM\Column]
    private ?int $initiative = null;

    #[ORM\Column]
    private ?int $tackle = null;

    #[ORM\Column]
    private ?int $dodge = null;

    #[ORM\Column]
    private ?int $parry = null;

    #[ORM\Column]
    private ?int $criticalHit = null;

    #[ORM\Column]
    private ?int $attackWater = null;

    #[ORM\Column]
    private ?int $attackEarth = null;

    #[ORM\Column]
    private ?int $attackWind = null;

    #[ORM\Column]
    private ?int $attackFire = null;

    #[ORM\Column]
    private ?int $resWater = null;

    #[ORM\Column]
    private ?int $resEarth = null;

    #[ORM\Column]
    private ?int $resWind = null;

    #[ORM\Column]
    private ?int $resFire = null;

    #[ORM\Column]
    private ?int $levelMin = null;

    #[ORM\Column]
    private ?int $levelMax = null;

    #[ORM\Column]
    private ?bool $isCapturable = null;

    #[ORM\ManyToOne(inversedBy: 'Mobs')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Family $family = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

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

    public function getActionPoints(): ?int
    {
        return $this->actionPoints;
    }

    public function setActionPoints(?int $actionPoints): static
    {
        $this->actionPoints = $actionPoints;

        return $this;
    }

    public function getMovementPoints(): ?int
    {
        return $this->movementPoints;
    }

    public function setMovementPoints(?int $movementPoints): static
    {
        $this->movementPoints = $movementPoints;

        return $this;
    }

    public function getInitiative(): ?int
    {
        return $this->initiative;
    }

    public function setInitiative(?int $initiative): static
    {
        $this->initiative = $initiative;

        return $this;
    }

    public function getTackle(): ?int
    {
        return $this->tackle;
    }

    public function setTackle(?int $tackle): static
    {
        $this->tackle = $tackle;

        return $this;
    }

    public function getDodge(): ?int
    {
        return $this->dodge;
    }

    public function setDodge(?int $dodge): static
    {
        $this->dodge = $dodge;

        return $this;
    }

    public function getParry(): ?int
    {
        return $this->parry;
    }

    public function setParry(?int $parry): static
    {
        $this->parry = $parry;

        return $this;
    }

    public function getCriticalHit(): ?int
    {
        return $this->criticalHit;
    }

    public function setCriticalHit(?int $criticalHit): static
    {
        $this->criticalHit = $criticalHit;

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

    public function getAttackEarth(): ?int
    {
        return $this->attackEarth;
    }

    public function setAttackEarth(?int $attackEarth): static
    {
        $this->attackEarth = $attackEarth;

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

    public function getAttackFire(): ?int
    {
        return $this->attackFire;
    }

    public function setAttackFire(?int $attackFire): static
    {
        $this->attackFire = $attackFire;

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

    public function getResWind(): ?int
    {
        return $this->resWind;
    }

    public function setResWind(?int $resWind): static
    {
        $this->resWind = $resWind;

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

    public function getLevelMin(): ?int
    {
        return $this->levelMin;
    }

    public function setLevelMin(int $levelMin): static
    {
        $this->levelMin = $levelMin;

        return $this;
    }

    public function getLevelMax(): ?int
    {
        return $this->levelMax;
    }

    public function setLevelMax(int $levelMax): static
    {
        $this->levelMax = $levelMax;

        return $this;
    }

    public function isIsCapturable(): ?bool
    {
        return $this->isCapturable;
    }

    public function setIsCapturable(bool $isCapturable): static
    {
        $this->isCapturable = $isCapturable;

        return $this;
    }

    public function getFamily(): ?Family
    {
        return $this->family;
    }

    public function setFamily(?Family $family): static
    {
        $this->family = $family;

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
}