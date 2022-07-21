<?php

namespace App\Entity;

use App\Repository\KhateebRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: KhateebRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: 'This {{ label }} is already taken.',
)]

class Khateeb
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $name;

    #[ORM\ManyToMany(targetEntity: Mosque::class, inversedBy: 'yes')]
    public $mosque;

    public function __construct()
    {
        $this->mosque = new ArrayCollection();
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
    
    /**
     * @return Collection<int, Mosque>
     */
    public function getMosque(): Collection
    {
        return $this->mosque;
    }

    public function addMosque(Mosque $mosque): self
    {
        if (!$this->mosque->contains($mosque)) {
            $this->mosque[] = $mosque;
        }

        return $this;
    }

    public function removeMosque(Mosque $mosque): self
    {
        $this->mosque->removeElement($mosque);

        return $this;
    }

}
