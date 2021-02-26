<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $forename;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statutm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nationalie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fonction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lettrem;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $other;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $societyname;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=17, nullable=true)
     */
    private $telnumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;
    
    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\ManyToOne(targetEntity=Localite::class, inversedBy="users")
     */
    private $localite;

    /**
     * @ORM\ManyToOne(targetEntity=Domaine::class, inversedBy="users")
     */
    private $domaine;

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="candidat")
     */
    private $candidatures;

    /**
     * @ORM\OneToMany(targetEntity=Abonnement::class, mappedBy="user")
     */
    private $abonnements;

    /**
     * @ORM\OneToMany(targetEntity=Asksubscibe::class, mappedBy="recruteur")
     */
    private $asksubscibes;

    /**
     * @ORM\OneToMany(targetEntity=Emploi::class, mappedBy="recruteur")
     */
    private $emplois;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->abonnements = new ArrayCollection();
        $this->asksubscibes = new ArrayCollection();
        $this->emplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getForename(): ?string
    {
        return $this->forename;
    }

    public function setForename(string $forename): self
    {
        $this->forename = $forename;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getStatutm(): ?string
    {
        return $this->statutm;
    }

    public function setStatutm(?string $statutm): self
    {
        $this->statutm = $statutm;

        return $this;
    }

    public function getNationalie(): ?string
    {
        return $this->nationalie;
    }

    public function setNationalie(?string $nationalie): self
    {
        $this->nationalie = $nationalie;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getLettrem(): ?string
    {
        return $this->lettrem;
    }

    public function setLettrem(?string $lettrem): self
    {
        $this->lettrem = $lettrem;

        return $this;
    }

    public function getOther(): ?string
    {
        return $this->other;
    }

    public function setOther(?string $other): self
    {
        $this->other = $other;

        return $this;
    }

    public function getSocietyname(): ?string
    {
        return $this->societyname;
    }

    public function setSocietyname(?string $societyname): self
    {
        $this->societyname = $societyname;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTelnumber(): ?string
    {
        return $this->telnumber;
    }

    public function setTelnumber(string $telnumber): self
    {
        $this->telnumber = $telnumber;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get the value of apiToken
     */ 
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * Set the value of apiToken
     *
     * @return self
     */ 
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

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

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): self
    {
        $this->domaine = $domaine;

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
            $candidature->setCandidat($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getCandidat() === $this) {
                $candidature->setCandidat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Abonnement[]
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            $abonnement->setUser($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getUser() === $this) {
                $abonnement->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        $abo=$this->getForename().' '.$this->getName().' - '.$this->getSocietyname();
        return $abo;
        
    }

    /**
     * @return Collection|Asksubscibe[]
     */
    public function getAsksubscibes(): Collection
    {
        return $this->asksubscibes;
    }

    public function addAsksubscibe(Asksubscibe $asksubscibe): self
    {
        if (!$this->asksubscibes->contains($asksubscibe)) {
            $this->asksubscibes[] = $asksubscibe;
            $asksubscibe->setRecruteur($this);
        }

        return $this;
    }

    public function removeAsksubscibe(Asksubscibe $asksubscibe): self
    {
        if ($this->asksubscibes->removeElement($asksubscibe)) {
            // set the owning side to null (unless already changed)
            if ($asksubscibe->getRecruteur() === $this) {
                $asksubscibe->setRecruteur(null);
            }
        }

        return $this;
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
            $emploi->setRecruteur($this);
        }

        return $this;
    }

    public function removeEmploi(Emploi $emploi): self
    {
        if ($this->emplois->removeElement($emploi)) {
            // set the owning side to null (unless already changed)
            if ($emploi->getRecruteur() === $this) {
                $emploi->setRecruteur(null);
            }
        }

        return $this;
    }
}
