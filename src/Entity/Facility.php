<?php

namespace App\Entity;

use App\Repository\FacilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FacilityRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: 'This {{ value }} is already in use.',
)]

class Facility
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $image;

    #[ORM\ManyToMany(targetEntity: Mosque::class, inversedBy: 'facilities')]
    public $Mosque;

    public function __construct()
    {
        $this->Mosque = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Mosque>
     */
    public function getMosque(): Collection
    {
        return $this->Mosque;
    }

    public function addMosque(Mosque $mosque): self
    {
        if (!$this->Mosque->contains($mosque)) {
            $this->Mosque[] = $mosque;
        }

        return $this;
    }

    public function removeMosque(Mosque $mosque): self
    {
        $this->Mosque->removeElement($mosque);

        return $this;
    }
}
