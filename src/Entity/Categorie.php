<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Domaine::class, mappedBy="categorie")
     */
    private $domaines;

    /**
     * @ORM\OneToMany(targetEntity=Emploi::class, mappedBy="categorie")
     */
    private $emplois;

    public function __construct()
    {
        $this->domaines = new ArrayCollection();
        $this->emplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Domaine[]
     */
    public function getDomaines(): Collection
    {
        return $this->domaines;
    }

    public function addDomaine(Domaine $domaine): self
    {
        if (!$this->domaines->contains($domaine)) {
            $this->domaines[] = $domaine;
            $domaine->setCategorie($this);
        }

        return $this;
    }

    public function removeDomaine(Domaine $domaine): self
    {
        if ($this->domaines->removeElement($domaine)) {
            // set the owning side to null (unless already changed)
            if ($domaine->getCategorie() === $this) {
                $domaine->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
       
        return $this->getName();
        
    }

    /**
     * @return Collection|Emploi[]
     */
    public function getEmplois(): Collection
    {
        return $this->emplois;
    }

    public function addEmploi(Emploi $emploi): self
    {
        if (!$this->emplois->contains($emploi)) {
            $this->emplois[] = $emploi;
            $emploi->setCategorie($this);
        }

        return $this;
    }

    public function removeEmploi(Emploi $emploi): self
    {
        if ($this->emplois->removeElement($emploi)) {
            // set the owning side to null (unless already changed)
            if ($emploi->getCategorie() === $this) {
                $emploi->setCategorie(null);
            }
        }

        return $this;
    }
}
