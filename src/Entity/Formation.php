<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups("post:read")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("post:read")
     */
    private $formateur;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThanOrEqual("today")
     *  @Groups("post:read")
     */
    public $date_debut;

    /**
     * @ORM\Column(type="date")
     * @Assert\Expression (
     *     "this.getDateDebut() < this.getDateFin()",
     *     message="la date fin ne doit pas inferieur à la date debut"
     * )
     *  @Groups("post:read")
     */
    public $date_fin;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("post:read")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("post:read")
     */
    private $mail;

    /**
     * @ORM\Column(type="float")
     *  @Groups("post:read")
     */
    private $tel;

    /**
     * @ORM\Column(type="float")
     *  @Groups("post:read")
     */
    public $prix;


    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="formation")
     *  @Groups("post:read")
     */
    private $category;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getFormateur()
    {
        return $this->formateur;
    }

    /**
     * @param mixed $formateur
     */
    public function setFormateur($formateur): void
    {
        $this->formateur = $formateur;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * @param mixed $date_debut
     */
    public function setDateDebut($date_debut): void
    {
        $this->date_debut = $date_debut;
    }

    /**
     * @return mixed
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * @param mixed $date_fin
     */
    public function setDateFin($date_fin): void
    {
        $this->date_fin = $date_fin;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix): void
    {
        $this->prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }




}
