<?php

namespace App\Entity;

use App\Repository\SubzoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubzoneRepository::class)]
class Subzone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?int $levelMin = null;

    #[ORM\Column]
    private ?int $levelMax = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\ManyToOne(inversedBy: 'subzones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $Zone = null;

    #[ORM\ManyToMany(targetEntity: Resource::class, inversedBy: 'subzones')]
    private Collection $resources;

    #[ORM\ManyToMany(targetEntity: Family::class, inversedBy: 'subzones')]
    private Collection $mobs;

    #[ORM\OneToMany(mappedBy: 'subzone', targetEntity: Dungeon::class)]
    private Collection $dungeons;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->mobs = new ArrayCollection();
        $this->dungeons = new ArrayCollection();
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

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->Zone;
    }

    public function setZone(?Zone $Zone): static
    {
        $this->Zone = $Zone;

        return $this;
    }

    /**
     * @return Collection<int, resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
        }

        return $this;
    }

    public function removeResource(Resource $resource): static
    {
        $this->resources->removeElement($resource);

        return $this;
    }

    /**
     * @return Collection<int, Family>
     */
    public function getMobs(): Collection
    {
        return $this->mobs;
    }

    public function addMob(Family $mob): static
    {
        if (!$this->mobs->contains($mob)) {
            $this->mobs->add($mob);
        }

        return $this;
    }

    public function removeMob(Family $mob): static
    {
        $this->mobs->removeElement($mob);

        return $this;
    }

    /**
     * @return Collection<int, Dungeon>
     */
    public function getDungeons(): Collection
    {
        return $this->dungeons;
    }

    public function addDungeon(Dungeon $dungeon): static
    {
        if (!$this->dungeons->contains($dungeon)) {
            $this->dungeons->add($dungeon);
            $dungeon->setSubzone($this);
        }

        return $this;
    }

    public function removeDungeon(Dungeon $dungeon): static
    {
        if ($this->dungeons->removeElement($dungeon)) {
            // set the owning side to null (unless already changed)
            if ($dungeon->getSubzone() === $this) {
                $dungeon->setSubzone(null);
            }
        }

        return $this;
    }
}
