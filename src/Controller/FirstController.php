<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/template', name: 'tempmlate')]
    public function template(): Response
    {
        //Chercher au niveau  de la base vos utilisateur et les passés en parametre
        return $this->render('template.html.twig');
    }


    #[Route('/first', name: 'first')]
    public function index(): Response
    {
        //Chercher au niveau  de la base vos utilisateur et les passés en parametre
        return $this->render('first/index.html.twig',[
            'name'=>'AZANHOUN',
            'firstname'=>'Fiacre',
        ]);
    }

  

    //#[Route('/sayHello/{name}/{firstname}', name: 'say.hello')]
    public function sayHello(Request $request, $name ,$firstname): Response
    {
        return $this -> render('first/hello.html.twig',
        [
            'nom' => $name,
            'prenom' => $firstname,
       ]
    );
    }
    
}

