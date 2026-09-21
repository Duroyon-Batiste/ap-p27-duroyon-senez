<?php

namespace App\Entity;

use App\Repository\EntrepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepotRepository::class)]
class Entrepot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $ville = null;

    #[ORM\ManyToOne(inversedBy: 'entrepots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $zone = null;

    /**
     * @var Collection<int, StockEntrepot>
     */
    #[ORM\OneToMany(targetEntity: StockEntrepot::class, mappedBy: 'entrepot')]
    private Collection $stockEntrepots;

    public function __construct()
    {
        $this->stockEntrepots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * @return Collection<int, StockEntrepot>
     */
    public function getStockEntrepots(): Collection
    {
        return $this->stockEntrepots;
    }

    public function addStockEntrepot(StockEntrepot $stockEntrepot): static
    {
        if (!$this->stockEntrepots->contains($stockEntrepot)) {
            $this->stockEntrepots->add($stockEntrepot);
            $stockEntrepot->setEntrepot($this);
        }

        return $this;
    }

    public function removeStockEntrepot(StockEntrepot $stockEntrepot): static
    {
        if ($this->stockEntrepots->removeElement($stockEntrepot)) {
            // set the owning side to null (unless already changed)
            if ($stockEntrepot->getEntrepot() === $this) {
                $stockEntrepot->setEntrepot(null);
            }
        }

        return $this;
    }
}
