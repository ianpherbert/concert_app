<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VenueRepository;

/**
 * @ORM\Entity(repositoryClass=VenueRepository::class)
 */
class Venue
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Concert", mappedBy="venue")
     */
    private $concert;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=30, options={"default" : "Ma Salle de Spectacle"})
     */
    private $name = 'Ma Salle de Concert';


    /**
     * @ORM\Column(name="private", type="boolean")
     */
    private $isPrivate = false;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : "Mon Adresse"})
     */
    private $address = "Mon adresse";

    /**
     * @ORM\Column(type="string", length=30 , options={"default" : "Ma Ville"})
     */
    private $city = "Ma Ville";

    /**
     * @ORM\Column(type="string", length=30, nullable=true, options={"default" : "Ma region"} )
     */
    private $region = "Ma region";

    /**
     * @ORM\Column(type="string", length=30, options={"default" : "Mon Pays"})
     */
    private $country = "Mon Pays";

    /**
     * @ORM\Column(type="string", length=5,options={"default" : "00000"})
     */
    private $postal_code = "00000";

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $capacity = 0;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

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

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Concert[]
     */
    public function getConcert(): Collection
    {
        return $this->concert;
    }

    public function addConcert(Concert $concert): self
    {
        if (!$this->concert->contains($concert)) {
            $this->concert[] = $concert;
            $concert->setVenue($this);
        }

        return $this;
    }

    public function removeConcert(Concert $concert): self
    {
        if ($this->concert->removeElement($concert)) {
            // set the owning side to null (unless already changed)
            if ($concert->getVenue() === $this) {
                $concert->setVenue(null);
            }
        }

        return $this;
    }
}
