<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagasinRepository::class)]
class Magasin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $ville = null;

    #[ORM\ManyToOne(inversedBy: 'magasins')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $zone = null;

    /**
     * @var Collection<int, StockMagasin>
     */
    #[ORM\OneToMany(targetEntity: StockMagasin::class, mappedBy: 'Magasin')]
    private Collection $stockMagasins;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'magasin')]
    private Collection $commandes;

    public function __construct()
    {
        $this->stockMagasins = new ArrayCollection();
        $this->commandes = new ArrayCollection();
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
     * @return Collection<int, StockMagasin>
     */
    public function getStockMagasins(): Collection
    {
        return $this->stockMagasins;
    }

    public function addStockMagasin(StockMagasin $stockMagasin): static
    {
        if (!$this->stockMagasins->contains($stockMagasin)) {
            $this->stockMagasins->add($stockMagasin);
            $stockMagasin->setMagasin($this);
        }

        return $this;
    }

    public function removeStockMagasin(StockMagasin $stockMagasin): static
    {
        if ($this->stockMagasins->removeElement($stockMagasin)) {
            // set the owning side to null (unless already changed)
            if ($stockMagasin->getMagasin() === $this) {
                $stockMagasin->setMagasin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setMagasin($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getMagasin() === $this) {
                $commande->setMagasin(null);
            }
        }

        return $this;
    }
}
