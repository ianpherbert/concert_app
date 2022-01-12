<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcertRepository::class)
 */
class Concert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Venue",inversedBy="concert")
     */
    private $venue;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="concert")
     */
    private $bill;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->bill = new ArrayCollection();
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


    public function getVenue(): ?Venue
    {
        return $this->venue;
    }

    public function setVenue(?Venue $venue): self
    {
        $this->venue = $venue;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Bill[]
     */
    public function getBill(): Collection
    {
        return $this->bill;
    }

    public function addBill(Bill $bill): self
    {
        if (!$this->bill->contains($bill)) {
            $this->bill[] = $bill;
            $bill->setConcert($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bill->removeElement($bill)) {
            // set the owning side to null (unless already changed)
            if ($bill->getConcert() === $this) {
                $bill->setConcert(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }
}
