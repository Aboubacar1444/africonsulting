<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidatureRepository::class)
 */
class Candidature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Emploi::class, inversedBy="candidatures")
     */
    private $emploi;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="candidatures")
     */
    private $candidat;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $istreated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $decision;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploi(): ?Emploi
    {
        return $this->emploi;
    }

    public function setEmploi(?Emploi $emploi): self
    {
        $this->emploi = $emploi;

        return $this;
    }

    public function getCandidat(): ?User
    {
        return $this->candidat;
    }

    public function setCandidat(?User $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getIstreated(): ?string
    {
        return $this->istreated;
    }

    public function setIstreated(string $istreated): self
    {
        $this->istreated = $istreated;

        return $this;
    }

    public function getDecision(): ?string
    {
        return $this->decision;
    }

    public function setDecision(?string $decision): self
    {
        $this->decision = $decision;

        return $this;
    }
}
