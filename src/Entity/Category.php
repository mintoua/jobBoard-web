<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**


     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups("post:read")
     */
    private $id;

    /**

     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $titre;

    /**

     * @ORM\Column(type="string", length=255)
     *  @Groups("post:read")
     */
    public $descriptionc;

    /**
     * @ORM\OneToMany(targetEntity=Formation::class, mappedBy="category")
     */
    public $formation;

    /**

     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $couleur;

    /**
     * @ORM\OneToMany(targetEntity=OffreEmploi::class, mappedBy="categorie", orphanRemoval=true)
     */
    private $offreemplois;


    public function __toString()
    {
        return (string) $this->getTitre();
    }

    public function __construct()
    {
        $this->formation = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->offreemplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptionc(): ?string
    {
        return $this->descriptionc;
    }

    public function setDescriptionc(string $descriptionc): self
    {
        $this->descriptionc = $descriptionc;

        return $this;
    }

    /**
     * @return Collection|Formation[]
     */
    public function getFormation(): Collection
    {
        return $this->formation;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formation->contains($formation)) {
            $this->formation[] = $formation;
            $formation->setCategory($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formation->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getCategory() === $this) {
                $formation->setCategory(null);
            }
        }

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
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

    public function addOffreemplois(OffreEmploi $offreemplois): self
    {
        if (!$this->offreemplois->contains($offreemplois)) {
            $this->offreemplois[] = $offreemplois;
            $offreemplois->setCategorie($this);
        }

        return $this;
    }

    public function removeOffreemplois(OffreEmploi $offreemplois): self
    {
        if ($this->offreemplois->removeElement($offreemplois)) {
            // set the owning side to null (unless already changed)
            if ($offreemplois->getCategorie() === $this) {
                $offreemplois->setCategorie(null);
            }
        }

        return $this;
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