<?php

namespace App\Entity;

use App\Repository\DungeonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: DungeonRepository::class)]
class Dungeon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?int $max_player = null;

    #[ORM\Column(nullable: true)]
    private ?int $room_count = null;

    #[ORM\ManyToMany(targetEntity: Mobs::class, inversedBy: 'dungeons')]
    private Collection $Mobs;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\ManyToOne]
    private ?Mobs $Boss = null;

    public function __construct()
    {
        $this->Mobs = new ArrayCollection();
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

    public function getMaxPlayer(): ?int
    {
        return $this->max_player;
    }

    public function setMaxPlayer(?int $max_player): static
    {
        $this->max_player = $max_player;

        return $this;
    }

    public function getRoomCount(): ?int
    {
        return $this->room_count;
    }

    public function setRoomCount(?int $room_count): static
    {
        $this->room_count = $room_count;

        return $this;
    }

    /**
     * @return Collection<int, Mobs>
     */
    public function getMobs(): Collection
    {
        return $this->Mobs;
    }

    public function addMob(Mobs $mob): static
    {
        if (!$this->Mobs->contains($mob)) {
            $this->Mobs->add($mob);
        }

        return $this;
    }

    public function removeMob(Mobs $mob): static
    {
        $this->Mobs->removeElement($mob);

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

    public function getBoss(): ?Mobs
    {
        return $this->Boss;
    }

    public function setBoss(?Mobs $Boss): static
    {
        $this->Boss = $Boss;

        return $this;
    }
}
