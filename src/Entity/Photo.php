<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[UniqueEntity(
    fields: ['image'],
    message: 'This {{ value }} is already in use.',
)]

class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $image;

    #[ORM\ManyToOne(targetEntity: Mosque::class, inversedBy: 'photos')]
    public $mosque;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMosque(): Mosque
    {
        return $this->mosque;
    }

    public function setMosque(Mosque $mosque): self
    {
        $this->mosque = $mosque;

        return $this;
    }
}
