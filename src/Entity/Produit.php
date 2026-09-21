<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 18)]
    private ?string $ref = null;

    #[ORM\Column(length: 128)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fournisseur $fournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sport $sport = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'produit')]
    private Collection $photos;

    /**
     * @var Collection<int, StockEntrepot>
     */
    #[ORM\OneToMany(targetEntity: StockEntrepot::class, mappedBy: 'produit')]
    private Collection $stockEntrepots;

    /**
     * @var Collection<int, StockMagasin>
     */
    #[ORM\OneToMany(targetEntity: StockMagasin::class, mappedBy: 'produit')]
    private Collection $stockMagasins;

    /**
     * @var Collection<int, ProduitCommande>
     */
    #[ORM\OneToMany(targetEntity: ProduitCommande::class, mappedBy: 'produit')]
    private Collection $produitCommandes;

    /**
     * @var Collection<int, Panier>
     */
    #[ORM\OneToMany(targetEntity: Panier::class, mappedBy: 'produit')]
    private Collection $paniers;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->stockEntrepots = new ArrayCollection();
        $this->stockMagasins = new ArrayCollection();
        $this->produitCommandes = new ArrayCollection();
        $this->paniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setProduit($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProduit() === $this) {
                $photo->setProduit(null);
            }
        }

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
            $stockEntrepot->setProduit($this);
        }

        return $this;
    }

    public function removeStockEntrepot(StockEntrepot $stockEntrepot): static
    {
        if ($this->stockEntrepots->removeElement($stockEntrepot)) {
            // set the owning side to null (unless already changed)
            if ($stockEntrepot->getProduit() === $this) {
                $stockEntrepot->setProduit(null);
            }
        }

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
            $stockMagasin->setProduit($this);
        }

        return $this;
    }

    public function removeStockMagasin(StockMagasin $stockMagasin): static
    {
        if ($this->stockMagasins->removeElement($stockMagasin)) {
            // set the owning side to null (unless already changed)
            if ($stockMagasin->getProduit() === $this) {
                $stockMagasin->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProduitCommande>
     */
    public function getProduitCommandes(): Collection
    {
        return $this->produitCommandes;
    }

    public function addProduitCommande(ProduitCommande $produitCommande): static
    {
        if (!$this->produitCommandes->contains($produitCommande)) {
            $this->produitCommandes->add($produitCommande);
            $produitCommande->setProduit($this);
        }

        return $this;
    }

    public function removeProduitCommande(ProduitCommande $produitCommande): static
    {
        if ($this->produitCommandes->removeElement($produitCommande)) {
            // set the owning side to null (unless already changed)
            if ($produitCommande->getProduit() === $this) {
                $produitCommande->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getProduit() === $this) {
                $panier->setProduit(null);
            }
        }

        return $this;
    }
}
