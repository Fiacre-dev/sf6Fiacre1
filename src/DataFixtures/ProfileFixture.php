<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile ->setRs('Facebook');
        $profile ->setUrl('https://web.facebook.com/profile.php?id=100081345099697');

        $profile1 = new Profile();
        $profile1 ->setRs('Twitter');
        $profile1 ->setUrl('https://twitter.com/Fiacredev');


        $profile2 = new Profile();
        $profile2 ->setRs('LinkedIn');
        $profile2 ->setUrl('https://www.linkedin.com/in/fiacreazanhoun/');


        $profile3 = new Profile();
        $profile3 ->setRs('Github');
        $profile3 ->setUrl('https://github.com/Fiacre-dev');
        
        $manager->persist($profile);
        $manager->persist($profile2);
        $manager->persist($profile1);
        $manager->persist($profile3);
        $manager->flush();
    }
}
