<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['subzone'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['name'])]
    #[Groups(['subzone'])]
    private ?string $slug = null;

    #[ORM\Column]
    private ?int $levelMin = null;

    #[ORM\Column]
    private ?int $levelMax = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\OneToMany(mappedBy: 'Zone', targetEntity: Subzone::class, orphanRemoval: true)]
    private Collection $subzones;

    public function __construct()
    {
        $this->subzones = new ArrayCollection();
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

    /**
     * @return Collection<int, Subzone>
     */
    public function getSubzones(): Collection
    {
        return $this->subzones;
    }

    public function addSubzone(Subzone $subzone): static
    {
        if (!$this->subzones->contains($subzone)) {
            $this->subzones->add($subzone);
            $subzone->setZone($this);
        }

        return $this;
    }

    public function removeSubzone(Subzone $subzone): static
    {
        if ($this->subzones->removeElement($subzone)) {
            // set the owning side to null (unless already changed)
            if ($subzone->getZone() === $this) {
                $subzone->setZone(null);
            }
        }

        return $this;
    }
}
