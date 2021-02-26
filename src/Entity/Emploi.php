<?php

namespace App\Entity;

use App\Repository\EmploiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmploiRepository::class)
 */
class Emploi
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
    private $title;

    /**
     * @ORM\Column(type="string", length=2550)
     */
    private $mission;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $formation;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $experience;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $competence;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datepost;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datelimit;

    /**
     * @ORM\ManyToOne(targetEntity=Contrat::class, inversedBy="emplois")
     */
    private $contrat;

    /**
     * @ORM\ManyToOne(targetEntity=Localite::class, inversedBy="emplois")
     */
    private $localite;

    /**
     * @ORM\ManyToOne(targetEntity=Pays::class, inversedBy="emplois")
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $societyname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $extlink;

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="emploi")
     */
    private $candidatures;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="emplois")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="emplois")
     */
    private $recruteur;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMission(): ?string
    {
        return $this->mission;
    }

    public function setMission(string $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(?string $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getCompetence(): ?string
    {
        return $this->competence;
    }

    public function setCompetence(?string $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDatepost(): ?string
    {
        return $this->datepost;
    }

    public function setDatepost(string $datepost): self
    {
        $this->datepost = $datepost;

        return $this;
    }

    public function getDatelimit(): ?string
    {
        return $this->datelimit;
    }

    public function setDatelimit(string $datelimit): self
    {
        $this->datelimit = $datelimit;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getLocalite(): ?Localite
    {
        return $this->localite;
    }

    public function setLocalite(?Localite $localite): self
    {
        $this->localite = $localite;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getSocietyname(): ?string
    {
        return $this->societyname;
    }

    public function setSocietyname(string $societyname): self
    {
        $this->societyname = $societyname;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getExtlink(): ?string
    {
        return $this->extlink;
    }

    public function setExtlink(string $extlink): self
    {
        $this->extlink = $extlink;

        return $this;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setEmploi($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getEmploi() === $this) {
                $candidature->setEmploi(null);
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

    public function getRecruteur(): ?User
    {
        return $this->recruteur;
    }

    public function setRecruteur(?User $recruteur): self
    {
        $this->recruteur = $recruteur;

        return $this;
    }
}
