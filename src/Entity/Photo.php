<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
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
