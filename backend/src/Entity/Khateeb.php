<?php

namespace App\Entity;

use App\Repository\KhateebRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KhateebRepository::class)]
class Khateeb
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Mosque::class, inversedBy: 'khateebs')]
    private $name;

    public function __construct()
    {
        $this->name = new ArrayCollection();
        $this->mosque = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Mosque>
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(Mosque $name): self
    {
        if (!$this->name->contains($name)) {
            $this->name[] = $name;
        }

        return $this;
    }

    public function removeName(Mosque $name): self
    {
        $this->name->removeElement($name);

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
