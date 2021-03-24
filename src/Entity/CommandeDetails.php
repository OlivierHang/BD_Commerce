<?php

namespace App\Entity;

use App\Repository\CommandeDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeDetailsRepository::class)
 */
class CommandeDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="commandeDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bd;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getBd(): ?string
    {
        return $this->bd;
    }

    public function setBd(string $bd): self
    {
        $this->bd = $bd;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    // Pour avoir le prix par unité dans le backoffice
    public function getPrixDashboard(): ?float
    {
        $prix = $this->prix * 100;
        return $prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    // Pour avoir le prix Total dans le backoffice
    public function getTotalDashboard(): ?float
    {
        $total = $this->total * 100;
        return $total;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
