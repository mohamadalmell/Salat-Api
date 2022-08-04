<?php

namespace App\Entity;

use App\Repository\IqamaTimeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IqamaTimeRepository::class)]
class IqamaTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $fajr;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $dhuhur;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $asr;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $maghrib;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $ishaa;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public $day;

    #[ORM\OneToOne(targetEntity: Mosque::class, cascade: ['persist'])]
    #[Assert\NotBlank]
    public $mosque;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFajr(): ?string
    {
        return $this->fajr;
    }

    public function setFajr(string $fajr): self
    {
        $this->fajr = $fajr;

        return $this;
    }

    public function getDhuhur(): ?string
    {
        return $this->dhuhur;
    }

    public function setDhuhur(string $dhuhur): self
    {
        $this->dhuhur = $dhuhur;

        return $this;
    }

    public function getAsr(): ?string
    {
        return $this->asr;
    }

    public function setAsr(string $asr): self
    {
        $this->asr = $asr;

        return $this;
    }

    public function getMaghrib(): ?string
    {
        return $this->maghrib;
    }

    public function setMaghrib(string $maghrib): self
    {
        $this->maghrib = $maghrib;

        return $this;
    }

    public function getIshaa(): ?string
    {
        return $this->ishaa;
    }

    public function setIshaa(string $ishaa): self
    {
        $this->ishaa = $ishaa;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

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
