<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Mosque;

class MosqueFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $mosque = new Mosque();
        $mosque->setName('Salam');
        $mosque->setDescription('A mosque that has been built recently in Tripoli');
        $mosque->setAddress('Near Ogero');
        $mosque->setPhoneNumber('+96171846709');
        $mosque->setEmail('mohamad@gmail.com');
        $mosque->setName('Salam');

        //Add data to Pivot table
        $mosque->addKhateeb($this->getReference('khateeb_1'));

        $manager->persist($mosque);

        $manager->flush();

    }
}
