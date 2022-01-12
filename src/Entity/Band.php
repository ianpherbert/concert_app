<?php

namespace App\Entity;

use App\Repository\BandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BandRepository::class)
 */
class Band
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="band")
     */
    private $concert;

    /**
     * @ORM\Column(type="string", length=30, options={"default" : "Nom de groupe"})
     */
    private $name = "Mon Groupe";

    /**
     * @ORM\Column(name="private", type="boolean")
     */
    private $isPrivate = false;


    /**
     * @ORM\Column(type="string", length=30,options={"default" : "Ma Ville"})
     */
    private $city = " Ma Ville";

    /**
     * @ORM\Column(type="string", length=30, nullable=true, options={"default" : "Ma Region"})
     */
    private $region = "Ma Region";

    /**
     * @ORM\Column(type="string", length=30, options={"default" : "Mon Pays"})
     */
    private $country = "Mon Pays";

    /**
     * @ORM\Column(type="string", length=255, nullable=true , options={"default" : "A propos de nous. Ici vous pouvez ecrire un petit mot pour vous presenter."})
     */
    private $bio = "A propos de nous. Ici vous pouvez ecrire un petit mot pour vous presenter.";

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"default" : "/graphics/placeholder.jpg"})
     */
    private $photo;

    public function __construct()
    {
        $this->concert = new ArrayCollection();
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function setIsPrivate(bool $isPrivate): self
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|Bill[]
     */
    public function getConcert(): Collection
    {
        return $this->concert;
    }

    public function addConcert(Bill $concert): self
    {
        if (!$this->concert->contains($concert)) {
            $this->concert[] = $concert;
            $concert->setBand($this);
        }

        return $this;
    }

    public function removeConcert(Bill $concert): self
    {
        if ($this->concert->removeElement($concert)) {
            // set the owning side to null (unless already changed)
            if ($concert->getBand() === $this) {
                $concert->setBand(null);
            }
        }

        return $this;
    }
}
