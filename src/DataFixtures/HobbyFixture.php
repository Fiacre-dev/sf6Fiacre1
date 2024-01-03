<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data =[
            "Yoga",
            "Cuisine",
            "Patisserie",
            "Photographie",
            "Blogging",
            "Lecture",
            "Apprendre une langue",
            "Construction Lego",
            "Dessin",
            "Calorage",
            "Peinture",
            "Se lancer dans le tissage de tapis",
            "Créer des vetement ou des cosplay",
            "Fabriquer des bijoux",
            "Travailler le metal",
            "Décorer des galets",
            "Faire des puzzles avec de plus en plus de pieces",
            "Créer des miniatures (maison,voitures,trains,bateaux ...",
            "Améliorer son espace de vie",
            "Apprendre à jongler",
            "Faire partie d'un club de lecture",
            "Apprendre la programmaton informatque",
            "En apprendre plus sur le survivalisme",
            "Créer une chaine Youtube",
            "Jouer au fléchettes",
            "Apprendre à chanter"
        ];
        for($i=0; $i<count($data); $i++){
            $hobby = new Hobby();
            $hobby ->setDesignation ($data[$i]);
            $manager->persist($hobby);

        }

        $manager->flush();
    }
}
