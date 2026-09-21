<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Entrepot>
     */
    #[ORM\OneToMany(targetEntity: Entrepot::class, mappedBy: 'zone')]
    private Collection $entrepots;

    /**
     * @var Collection<int, Magasin>
     */
    #[ORM\OneToMany(targetEntity: Magasin::class, mappedBy: 'zone')]
    private Collection $magasins;

    public function __construct()
    {
        $this->entrepots = new ArrayCollection();
        $this->magasins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Entrepot>
     */
    public function getEntrepots(): Collection
    {
        return $this->entrepots;
    }

    public function addEntrepot(Entrepot $entrepot): static
    {
        if (!$this->entrepots->contains($entrepot)) {
            $this->entrepots->add($entrepot);
            $entrepot->setZone($this);
        }

        return $this;
    }

    public function removeEntrepot(Entrepot $entrepot): static
    {
        if ($this->entrepots->removeElement($entrepot)) {
            // set the owning side to null (unless already changed)
            if ($entrepot->getZone() === $this) {
                $entrepot->setZone(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Magasin>
     */
    public function getMagasins(): Collection
    {
        return $this->magasins;
    }

    public function addMagasin(Magasin $magasin): static
    {
        if (!$this->magasins->contains($magasin)) {
            $this->magasins->add($magasin);
            $magasin->setZone($this);
        }

        return $this;
    }

    public function removeMagasin(Magasin $magasin): static
    {
        if ($this->magasins->removeElement($magasin)) {
            // set the owning side to null (unless already changed)
            if ($magasin->getZone() === $this) {
                $magasin->setZone(null);
            }
        }

        return $this;
    }
}
