<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="idUser",referencedColumnName="id",nullable=false)
     * @ORM\Column(type="integer")
     */
    private $idUser;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPayment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    function __construct($idUser,$totalPayment,$state,$date){
        $this->setIdUser($idUser);
        $this->setTotalPayment($totalPayment);
        $this->setState($state);
        $this->setDate($date);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getTotalPayment(): ?float
    {
        return $this->totalPayment;
    }

    public function setTotalPayment(float $totalPayment): self
    {
        $this->totalPayment = $totalPayment;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }
}
