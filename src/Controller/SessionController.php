<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'session')]
    public function index(Request $request): Response
    {
        $session=$request -> getSession();//Recuperation de la session son equivalence en php est session_start()
        if($session -> has(name:'nbVisite')){
            $nbreVisite= $session->get(name:'nbVisite') + 1;
            //Mettons a jour nbreVisite par la nouvelle valeur incrementé
            $session->set('nbVisite',$nbreVisite);
        }else{
            $nbreVisite=1;
        }
        $session->set('nbVisite',$nbreVisite);
        return $this->render('session/index.html.twig');
    }
}
