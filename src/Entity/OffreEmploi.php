<?php

namespace App\Entity;

use App\Repository\OffreEmploiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OffreEmploiRepository::class)
 */
class OffreEmploi
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $idCandidat;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="title is required."))
     * @Assert\Length(
     *      min = "6",
     *      max = "50",
     *      minMessage = "{{ limit }} is the min letters"
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="poste is required."))
     * @Assert\Length(
     *      min = "6",
     *      max = "50",
     *      minMessage = "{{ limit }} is the min letters"
     * )
     * @Assert\NotBlank(message="Email is required")
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = "8",
     *      max = "50",
     *      minMessage = "{{ limit }} is the min letters"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "vacancy.date.valid",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "vacancy.date.not_today"
     * )
     */
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "vacancy.date.valid",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "vacancy.date.not_today"
     * )
     * @Assert\Expression(
     *     "this.getDateExpiration() >= this.getDateDebut()",
     *     message="expiration date nedds to be greater than today"
     * )
     */
    private $date_expiration;



    /**
     * @ORM\Column(type="integer", nullable=true)
     * * @Assert\Range(
     *      min = 100,
     *      max = 9999,
     *      notInRangeMessage = "You must be between {{ min }} $ and {{ max }} $ ",
     * )
     * @Assert\Expression(
     *     "this.getMaxSalary() <= this.getMinSalary()",
     *     message="min salary is bigger than max salary"
     * )
     */
    private $maxSalary;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * * @Assert\Range(
     *      min = 100,
     *      max = 9999,
     *      notInRangeMessage = "You must be between {{ min }} $ and {{ max }} $ ",
     * )
     * @Assert\Expression(
     *     "this.getMaxSalary() <= this.getMinSalary()",
     *     message="min salary is bigger than max salary"
     * )
     */
    private $minSalary;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="offreEmplois")
     */
    private $idRecruteur;

    /**
     * @ORM\OneToMany(targetEntity=DemandeRecrutement::class, mappedBy="offre", orphanRemoval=true)
     */
    private $applies;

    /**
     * @ORM\ManyToOne(targetEntity=categorie::class, inversedBy="offreemplois")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;


    public function __construct()
    {
        $this->applies = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdRecruteur(): ?User
    {
        return $this->idRecruteur;
    }

    public function setIdRecruteur(?User $idUser): self
    {
        $this->idRecruteur = $idUser;

        return $this;
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

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->date_expiration;
    }

    public function setDateExpiration(\DateTimeInterface $date_expiration): self
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }

    public function getIdCandidat(): ?User
    {
        return $this->idCandidat;
    }

    public function setIdCandidat(?User $idCandidat): self
    {
        $this->idCandidat = $idCandidat;

        return $this;
    }

    public function getMaxSalary(): ?int
    {
        return $this->maxSalary;
    }

    public function setMaxSalary(?int $maxSalary): self
    {
        $this->maxSalary = $maxSalary;

        return $this;
    }

    public function getMinSalary(): ?int
    {
        return $this->minSalary;
    }

    public function setMinSalary(?int $minSalary): self
    {
        $this->minSalary = $minSalary;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|DemandeRecrutement[]
     */
    public function getApplies(): Collection
    {
        return $this->applies;
    }

    public function addApply(DemandeRecrutement $apply): self
    {
        if (!$this->applies->contains($apply)) {
            $this->applies[] = $apply;
            $apply->setOffre($this);
        }

        return $this;
    }

    public function removeApply(DemandeRecrutement $apply): self
    {
        if ($this->applies->removeElement($apply)) {
            // set the owning side to null (unless already changed)
            if ($apply->getOffre() === $this) {
                $apply->setOffre(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
