<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $adresseLivraison = null;

    /**
     * @var Collection<int, HistoriqueEtat>
     */
    #[ORM\OneToMany(targetEntity: HistoriqueEtat::class, mappedBy: 'commande')]
    private Collection $historiqueEtats;

    /**
     * @var Collection<int, ProduitCommande>
     */
    #[ORM\OneToMany(targetEntity: ProduitCommande::class, mappedBy: 'commande')]
    private Collection $produitCommandes;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Magasin $magasin = null;

    public function __construct()
    {
        $this->historiqueEtats = new ArrayCollection();
        $this->produitCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(?string $adresseLivraison): static
    {
        $this->adresseLivraison = $adresseLivraison;

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueEtat>
     */
    public function getHistoriqueEtats(): Collection
    {
        return $this->historiqueEtats;
    }

    public function addHistoriqueEtat(HistoriqueEtat $historiqueEtat): static
    {
        if (!$this->historiqueEtats->contains($historiqueEtat)) {
            $this->historiqueEtats->add($historiqueEtat);
            $historiqueEtat->setCommande($this);
        }

        return $this;
    }

    public function removeHistoriqueEtat(HistoriqueEtat $historiqueEtat): static
    {
        if ($this->historiqueEtats->removeElement($historiqueEtat)) {
            // set the owning side to null (unless already changed)
            if ($historiqueEtat->getCommande() === $this) {
                $historiqueEtat->setCommande(null);
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
            $produitCommande->setCommande($this);
        }

        return $this;
    }

    public function removeProduitCommande(ProduitCommande $produitCommande): static
    {
        if ($this->produitCommandes->removeElement($produitCommande)) {
            // set the owning side to null (unless already changed)
            if ($produitCommande->getCommande() === $this) {
                $produitCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getMagasin(): ?Magasin
    {
        return $this->magasin;
    }

    public function setMagasin(?Magasin $magasin): static
    {
        $this->magasin = $magasin;

        return $this;
    }
}
