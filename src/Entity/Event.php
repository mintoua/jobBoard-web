<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez remplir ce champs")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="veuillez entrer la description")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="veuillez entrer le prix")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez entrer l'adresse")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez insérer une image")
     */
    private $image;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Assert\NotBlank(message="veuillez entrer la capacité")
     */
    private $nbrePlace;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getNbrePlace(): ?int
    {
        return $this->nbrePlace;
    }

    public function setNbrePlace(int $nbrePlace): self
    {
        $this->nbrePlace = $nbrePlace;

        return $this;
    }
}
