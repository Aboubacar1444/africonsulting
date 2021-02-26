<?php

namespace App\Entity;

use App\Repository\AsksubscibeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AsksubscibeRepository::class)
 */
class Asksubscibe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="asksubscibes")
     */
    private $recruteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $abonnement;

    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paiment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $data;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecruteur(): ?User
    {
        return $this->recruteur;
    }

    public function setRecruteur(?User $recruteur): self
    {
        $this->recruteur = $recruteur;

        return $this;
    }

    public function getAbonnement(): ?string
    {
        return $this->abonnement;
    }

    public function setAbonnement(string $abonnement): self
    {
        $this->abonnement = $abonnement;

        return $this;
    }

   
    public function getPaiment(): ?string
    {
        return $this->paiment;
    }

    public function setPaiment(string $paiment): self
    {
        $this->paiment = $paiment;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
