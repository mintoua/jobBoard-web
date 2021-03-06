<?php

namespace App\Entity;

use App\Repository\DemandeRecrutementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemandeRecrutementRepository::class)
 */
class DemandeRecrutement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=offreemploi::class, inversedBy="applies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offre;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="applies")
     */
    private $candidat;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateexpiration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffre(): ?offreemploi
    {
        return $this->offre;
    }

    public function setOffre(?offreemploi $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getCandidat(): ?user
    {
        return $this->candidat;
    }

    public function setCandidat(?user $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateexpiration(): ?\DateTimeInterface
    {
        return $this->dateexpiration;
    }

    public function setDateexpiration(\DateTimeInterface $dateexpiration): self
    {
        $this->dateexpiration = $dateexpiration;

        return $this;
    }
}
