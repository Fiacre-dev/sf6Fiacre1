<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Personne; // Assure-toi que le chemin d'accès à Personne est correct

class PersonneFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $personne = new Personne();
            $personne->setFirstname($faker->firstName); // Utilisation de firstName au lieu de firstname
            $personne->setName($faker->lastName); // Utilisation de lastName au lieu de name
            $personne->setAge($faker->numberBetween(18, 65));

            $manager->persist($personne);
        }

        $manager->flush();
    }
}
