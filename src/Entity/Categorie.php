<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couleur;

    /**
     * @ORM\OneToMany(targetEntity=OffreEmploi::class, mappedBy="categorie")
     */
    private $offreemplois;

    public function __construct()
    {
        $this->offreemplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * @return Collection|OffreEmploi[]
     */
    public function getOffreemplois(): Collection
    {
        return $this->offreemplois;
    }

    public function addOffreemploi(OffreEmploi $offreemploi): self
    {
        if (!$this->offreemplois->contains($offreemploi)) {
            $this->offreemplois[] = $offreemploi;
            $offreemploi->setCategorie($this);
        }

        return $this;
    }

    public function removeOffreemploi(OffreEmploi $offreemploi): self
    {
        if ($this->offreemplois->removeElement($offreemploi)) {
            // set the owning side to null (unless already changed)
            if ($offreemploi->getCategorie() === $this) {
                $offreemploi->setCategorie(null);
            }
        }

        return $this;
    }
}
