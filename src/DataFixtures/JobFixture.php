<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Job; // Assurez-vous d'importer l'entité Job


;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $data = [
            "Data scientist",
            "Statisticien",
            "Analyste cyber-sécurité",
            "Médecin ORL",
            "Echographiste",
            "Mathématicien",
            "Ingénieur logiciel",
            "Analyste Informatique",
            "Pathologiste du discours / langage",
            "Actuaire",
            "Ergothérapeute",
            "Directeur des Ressources Humaines",
            "Hygiéniste dentaire"
        ];
        for($i=0; $i<count($data); $i++){
            $job = new Job();
            $job ->setDesignation($data[$i]);
            $manager->persist($job);

        }
        

        $manager->flush();
    }
}
