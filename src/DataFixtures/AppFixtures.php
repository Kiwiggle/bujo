<?php

namespace App\DataFixtures;

use App\Entity\Mood;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i<30; $i++) {
            $mood = new Mood();
            $mood->setFeeling(Mood::FEELING[mt_rand(0, 3)]);
            $mood->setDate(new \DateTime(mt_rand(1589234400, 1590530400)));
            $mood->setGratitude("Gratitude");
            $mood->setNote("Note");
            $manager->persist($mood);
        }

        $manager->flush();
    }
}
