<?php

namespace App\Entity;

use App\Repository\IqamaTimeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IqamaTimeRepository::class)]
class IqamaTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
    public $fajr;

    #[ORM\Column(type: 'string', length: 255)]
    public $dhuhur;

    #[ORM\Column(type: 'string', length: 255)]
    public $asr;

    #[ORM\Column(type: 'string', length: 255)]
    public $maghrib;

    #[ORM\Column(type: 'string', length: 255)]
    public $ishaa;

    #[ORM\Column(type: 'string', length: 255)]
    public $day;

    #[ORM\OneToOne(targetEntity: Mosque::class, cascade: ['persist', 'remove'])]
    public $mosque;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFajr(): ?string
    {
        return $this->Fajr;
    }

    public function setFajr(string $Fajr): self
    {
        $this->Fajr = $Fajr;

        return $this;
    }

    public function getDhuhur(): ?string
    {
        return $this->Dhuhur;
    }

    public function setDhuhur(string $Dhuhur): self
    {
        $this->Dhuhur = $Dhuhur;

        return $this;
    }

    public function getAsr(): ?string
    {
        return $this->Asr;
    }

    public function setAsr(string $Asr): self
    {
        $this->Asr = $Asr;

        return $this;
    }

    public function getMaghrib(): ?string
    {
        return $this->Maghrib;
    }

    public function setMaghrib(string $Maghrib): self
    {
        $this->Maghrib = $Maghrib;

        return $this;
    }

    public function getIshaa(): ?string
    {
        return $this->Ishaa;
    }

    public function setIshaa(string $Ishaa): self
    {
        $this->Ishaa = $Ishaa;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->Day;
    }

    public function setDay(string $Day): self
    {
        $this->Day = $Day;

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
