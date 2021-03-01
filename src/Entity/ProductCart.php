<?php

namespace App\Entity;

use App\Repository\ProductCartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductCartRepository::class)
 */
class ProductCart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="idOrder",referencedColumnName="id",nullable=false)
     */
    private $idOrder;

    /**
     * @ORM\OneToOne(targetEntity=Products::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="idProduct",referencedColumnName="id",nullable=false)
     */
    private $idProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    function __construct($idOrder,$idProduct,$quantity){
        $this->setIdOrder($idOrder);
        $this->setIdProduct($idProduct);
        $this->setQuantity($quantity);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): ?Order
    {
        return $this->idOrder;
    }

    public function setIdOrder($idOrder): self
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    public function getIdProduct(): ?Product
    {
        return $this->idProduct;
    }

    public function setIdProduct($idProduct): self
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
