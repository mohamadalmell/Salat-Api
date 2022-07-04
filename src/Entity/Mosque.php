<?php

namespace App\Entity;

use App\Repository\MosqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MosqueRepository::class)]
class Mosque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\Column(type: 'integer')]
    private $phoneNumber;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email;

    #[ORM\ManyToMany(targetEntity: Facility::class, mappedBy: 'Mosque')]
    private $facilities;

    public function __construct()
    {
        $this->facilities = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Facility>
     */
    public function getFacilities(): Collection
    {
        return $this->facilities;
    }

    public function addFacility(Facility $facility): self
    {
        if (!$this->facilities->contains($facility)) {
            $this->facilities[] = $facility;
            $facility->addMosque($this);
        }

        return $this;
    }

    public function removeFacility(Facility $facility): self
    {
        if ($this->facilities->removeElement($facility)) {
            $facility->removeMosque($this);
        }

        return $this;
    }
}
