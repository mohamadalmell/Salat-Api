<?php

namespace App\Entity;

use App\Repository\MosqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MosqueRepository::class)]
#[UniqueEntity(
    fields: ['name', 'phone_number', 'email'],
    message: 'This {{ value }} is already in use.',
)]

class Mosque 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank]
    public $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $address;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    public $phoneNumber;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    
    public $email;

    #[ORM\ManyToMany(targetEntity: Facility::class, mappedBy: 'Mosque')]
    public $facilities;

    #[ORM\OneToMany(mappedBy: 'mosque', targetEntity: Photo::class)]
    public $photos;

    #[ORM\ManyToMany(targetEntity: Khateeb::class, mappedBy: 'mosque')]
    public $khateebs;

    public function __construct()
    {
        $this->facilities = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->khateebs = new ArrayCollection();
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

    public function getAll() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
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

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setMosque($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getMosque() === $this) {
                $photo->setMosque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Khateeb>
     */
    public function getKhateebs(): Collection
    {
        return $this->khateebs;
    }

    public function addKhateeb(Khateeb $khateeb): self
    {
        if (!$this->khateebs->contains($khateeb)) {
            $this->khateebs[] = $khateeb;
            $khateeb->addMosque($this);
        }

        return $this;
    }

    public function removeKhateeb(Khateeb $khateeb): self
    {
        if ($this->khateebs->removeElement($khateeb)) {
            $khateeb->removeMosque($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Khateeb>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Khateeb $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->addMosque($this);
        }

        return $this;
    }

    public function removeYe(Khateeb $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            $ye->removeMosque($this);
        }

        return $this;
    }
}
