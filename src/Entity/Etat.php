<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
class Etat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $nom = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, HistoriqueEtat>
     */
    #[ORM\OneToMany(targetEntity: HistoriqueEtat::class, mappedBy: 'etat')]
    private Collection $historiqueEtats;

    public function __construct()
    {
        $this->historiqueEtats = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
            $historiqueEtat->setEtat($this);
        }

        return $this;
    }

    public function removeHistoriqueEtat(HistoriqueEtat $historiqueEtat): static
    {
        if ($this->historiqueEtats->removeElement($historiqueEtat)) {
            // set the owning side to null (unless already changed)
            if ($historiqueEtat->getEtat() === $this) {
                $historiqueEtat->setEtat(null);
            }
        }

        return $this;
    }
}
