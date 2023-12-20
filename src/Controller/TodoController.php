<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/todo")]
class TodoController extends AbstractController
{
    #[Route("/", name: "todo")]
    //Injection de l'objet Request
    public function index(Request $request): Response
    {
        $session=$request->getSession();
        //Afficher notre tableau de todo
        //sinon je l'initialise puis j'affiche
        if(!$session->has(name:'todos')){
            $todos=[
                'achat'=>'acheter clé usb',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            //Mettons le tableau dans ma session todos creer.
            $session->set('todos',$todos);
            $this->addFlash('info',"La liste des todos viens d'etre initialisée");
        }
        //Si j'ai mon tableau{A travers de la methode has() de la session?}  de todo dans ma session je ne fait que l'afficher
        return $this->render('todo/index.html.twig');
    }
    //Association de route à cette  fonctionnalité addTodo
    #[Route('/add/{name}/{content}', name: 'todo.add')]
    public function addTodo(Request $request,$name,$content): RedirectResponse{
        //Voici l'algorithme à utiliser pour gerer notre todo
        $session=$request->getSession();//Recuperation de l'objet request et  getSession pour recuperer ma session
        //Verifier j'ai mon tableau de todo dans la session
        if($session->has(name:'todos')){
             //Si oui
                //Verifier si on a deja un todo avec le meme name
                $todos= $session->get('todos');
                //Testons cela,isset()verifier l'existence d'une variable
                if(isset($todos[$name])){
                     //si oui afficher erreur
                    $this->addFlash('error',"Le todo d'id $name  existe deja dans la liste");
                }else{
                    //sinon on l'ajoute et on affiche un message de succes
                    $todos[$name]=$content;
                    $session->set('todos',$todos);
                    $this->addFlash('succes',"Le todo d'id $name a ete ajouté avec succes");
                    //Mise à jour du tab
                }
        }else{
              //Si non
             //afficher une erreur et on  va rediriger vers le controller initial (le controller index)
             $this->addFlash('error',"La liste des todos n'est pas encore  initialisée");
        }
        //Affichage de la liste todo soit avec forword ou redirect
        return $this->redirectToRoute('todo/index.html.twig');
    }

    #[Route('/update/{name}/{content}',name:'todo.update')]
    public function updateTodo(Request $request,$name,$content): RedirectResponse{
        //Voici l'algorithme à utiliser pour gerer notre todo
        $session=$request->getSession();//Recuperation de l'objet request et  getSession pour recuperer ma session
        //Verifier j'ai mon tableau de todo dans la session
        if($session->has(name:'todos')){
             //Si oui
                //Verifier si on a deja un todo avec le meme name
                $todos= $session->get('todos');
                //Testons cela,isset()verifier l'existence d'une variable
                if(isset($todos[$name])){
                     //si oui afficher erreur
                    $this->addFlash('error',"Le todo d'id $name n'existe pas dans la liste");
                }else{
                    //sinon on l'ajoute et on affiche un message de succes
                    $todos[$name]=$content;
                    $session->set('todos',$todos);
                    $this->addFlash('succes',"Le todo d'id $name a été modifié avec succès");
                    //Mise à jour du tab
                }
        }else{
              //Si non

             //afficher une erreur et on  va rediriger vers le controller initial (le controller index)
             $this->addFlash('error',"La liste des todos n'est pas encore  initialisée");
        }
        //Affichage de la liste todo soit avec forword ou redirect
        return $this->redirectToRoute('todo_index');
    }

    #[Route('/delete/{name}',name:'todo.delete')]
    public function deleteTodo(Request $request,$name,): RedirectResponse{
        //Voici l'algorithme à utiliser pour gerer notre todo
        $session=$request->getSession();//Recuperation de l'objet request et  getSession pour recuperer ma session
        //Verifier j'ai mon tableau de todo dans la session
        if($session->has(name:'todos')){
             //Si oui
                //Verifier si on a deja un todo avec le meme name
                $todos= $session->get('todos');
                //Testons cela,isset()verifier l'existence d'une variable
                if(isset($todos[$name])){
                     //si oui afficher erreur
                    $this->addFlash('error',"Le todo d'id $name n'existe pas dans la liste");
                }else{
                    //sinon on l'ajoute et on affiche un message de succes
                    unset($todos[$name]);
                    $session->set('todos',$todos);
                    $this->addFlash('succes',"Le todo d'id $name a été supprimé avec succès");
                    //Mise à jour du tab
                }
        }else{
              //Si non

             //afficher une erreur et on  va rediriger vers le controller initial (le controller index)
             $this->addFlash('error',"La liste des todos n'est pas encore  initialisée");
        }
        //Affichage de la liste todo soit avec forword ou redirect
        return $this->redirectToRoute('todo_index');
    }
    #[Route('/reset',name:'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse{
        //Voici l'algorithme à utiliser pour gerer notre todo
        $session=$request->getSession();//Recuperation de l'objet request et  getSession pour recuperer ma session
        $session->remove('todos');
       
        //Affichage de la liste todo soit avec forword ou redirect
        return $this->redirectToRoute('todo_index');
    }
}
