<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\MobsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Filter\FullTextFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MobsRepository::class)]
#[ApiResource(operations: [
    new Get(
        normalizationContext:['groups' => ['mob:item', 'mob:drops', 'rarity', 'typeStuff', 'family']],
    ),
    new GetCollection(
        normalizationContext:['groups' => ['mob:search', 'family']]
    )
])]
#[ApiFilter(FullTextFilter::class, properties:['index' => 'mob', 'fields' => ['name^5', 'family.name']])]
class Mobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:drops', 'mob:item', 'stuff:drops', 'mob:search'])]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type : "string", length : 128, unique : false, nullable : true)]
    #[Groups(['resource:drops', 'mob:item', 'stuff:drops', 'mob:search'])]
    #[ApiProperty(identifier: true)]
    private ?string $slug = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $actionPoints = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $movementPoints = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $initiative = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $tackle = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $dodge = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $parry = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $criticalHit = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $attackWater = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $attackEarth = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $attackWind = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $attackFire = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $resWater = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $resEarth = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $resWind = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $resFire = null;

    #[ORM\Column]
    #[Groups(['resource:drops', 'mob:item', 'stuff:drops', 'mob:search'])]
    private ?int $levelMin = null;

    #[ORM\Column]
    #[Groups(['resource:drops', 'mob:item', 'stuff:drops', 'mob:search'])]
    private ?int $levelMax = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?bool $isCapturable = null;

    #[ORM\ManyToOne(inversedBy: 'Mobs')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['resource:drops', 'mob:item', 'stuff:drops'])]
    private ?Family $family = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:drops', 'stuff:drops', 'mob:item', 'mob:search'])]
    private ?string $imageUrl = null;

    #[ORM\Column]
    #[Groups('mob:item')]
    private ?int $hp = null;

    #[ORM\OneToMany(mappedBy: 'mob', targetEntity: ResourceDrop::class, orphanRemoval: true)]
    #[Groups('mob:drops')]
    private Collection $resourceDrops;

    #[ORM\OneToMany(mappedBy: 'mob', targetEntity: StuffDrop::class, orphanRemoval: true)]
    #[Groups('mob:drops')]
    private Collection $stuffDrops;

    public function __construct() {
        $this->actionPoints = 0;
        $this->movementPoints = 0;
        $this->initiative = 0;
        $this->tackle = 0;
        $this->dodge = 0;
        $this->parry = 0;
        $this->criticalHit = 0;
        $this->hp = 0;
        $this->resourceDrops = new ArrayCollection();
        $this->stuffDrops = new ArrayCollection();
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

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    /**
     * @return Collection<int, ResourceDrop>
     */
    public function getResourceDrops(): Collection
    {
        return $this->resourceDrops;
    }

    public function addResourceDrop(ResourceDrop $resourceDrop): static
    {
        if (!$this->resourceDrops->contains($resourceDrop)) {
            $this->resourceDrops->add($resourceDrop);
            $resourceDrop->setMob($this);
        }

        return $this;
    }

    public function removeResourceDrop(ResourceDrop $resourceDrop): static
    {
        if ($this->resourceDrops->removeElement($resourceDrop)) {
            // set the owning side to null (unless already changed)
            if ($resourceDrop->getMob() === $this) {
                $resourceDrop->setMob(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StuffDrop>
     */
    public function getStuffDrops(): Collection
    {
        return $this->stuffDrops;
    }

    public function addStuffDrop(StuffDrop $stuffDrop): static
    {
        if (!$this->stuffDrops->contains($stuffDrop)) {
            $this->stuffDrops->add($stuffDrop);
            $stuffDrop->setMob($this);
        }

        return $this;
    }

    public function removeStuffDrop(StuffDrop $stuffDrop): static
    {
        if ($this->stuffDrops->removeElement($stuffDrop)) {
            // set the owning side to null (unless already changed)
            if ($stuffDrop->getMob() === $this) {
                $stuffDrop->setMob(null);
            }
        }

        return $this;
    }
}