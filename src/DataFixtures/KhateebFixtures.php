<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Khateeb;

class KhateebFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $khateeb = new Khateeb();
        $khateeb->setName('Ahmad Ayoub');

        $manager->persist($khateeb);

        $manager->flush();

        $this->addReference('khateeb_1', $khateeb);
        
    }
}
