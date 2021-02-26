<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $theme;

    /**
     * @ORM\Column(type="string", length=2500, nullable=true)
     */
    private $module;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datepost;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datelimit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telnumber;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $multiimg = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(?string $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

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

    public function getTelnumber(): ?string
    {
        return $this->telnumber;
    }

    public function setTelnumber(string $telnumber): self
    {
        $this->telnumber = $telnumber;

        return $this;
    }

    public function getIsactive(): ?string
    {
        return $this->isactive;
    }

    public function setIsactive(string $isactive): self
    {
        $this->isactive = $isactive;

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

    public function getMultiimg(): ?array
    {
        return $this->multiimg;
    }

    public function setMultiimg(?array $multiimg): self
    {
        $this->multiimg = $multiimg;

        return $this;
    }
}
