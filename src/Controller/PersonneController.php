<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    //La liste
    #[Route('/', name: 'personne.list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }

    //La liste
 #[Route('/alls/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonnesByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes
        ]);
    }

    #[Route('/stats/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function statsPersonneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statsPersonnesByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/stats.html.twig', [
            'stats' => $stats[0],
            'ageMin' => $ageMin,
            'ageMax' => $ageMax
        ]);
    }
    

    //La liste avec la pagination
    #[Route('/alls/{page?1}/{nbre?12}', name: 'personne.list.alls')]
    public function indexalls(ManagerRegistry $doctrine, $page, $nbre): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]);
        
        $nbrepage = ceil($nbPersonne / $nbre);
 
        $personnes = $repository->findBy([], [], $nbre, ($page - 1) * $nbre);

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbrePage' => $nbrepage,
            'page' => $page,
            'nbre' => $nbre,
        ]);
    }

    //Les detailles d'une personne
    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(Personne $personne = null): Response
    {
        if (!$personne) {
            $this->addFlash('error', "La personne n'existe pas");
            return $this->redirectToRoute('personne.list');
        }
        return $this->render('personne/detail.html.twig', ['personne' => $personne]);
    }

    //Ajout d'une personne
    #[Route('/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setFirstname('Ficre');
        $personne->setName('AZANHOUN');
        $personne->setAge(25); // L'âge devrait être un nombre, pas une chaîne

        // Commenter la section suivante pour éviter une erreur
        /*
        $personne2 = new Personne();
        $personne2->setFirstname('Debora');
        $personne2->setName('ZANMENOU');
        $personne2->setAge(20);

        $entityManager->persist($personne2);
        */

       // $entityManager->persist($personne);
        $entityManager->flush();

        return $this->render('personne/detail.html.twig', ['personne' => $personne]);
    }

    //Suppression d'une personnes
    #[Route('/delete/{id}' ,name:'personne.delete')]
    public function deletePersonne(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse {
        // Récupérer la personne
        if ($personne) {
            // Si la personne existe => la supprimer et retourner un flashMessage de succès
            $manager = $doctrine->getManager();
            // Ajouter la fonction de suppression dans la transaction
            $manager->remove($personne);
            // Exécuter la transaction
            $manager->flush();
            $this->addFlash('success', "La personne a été supprimée avec succès");
        } else {
            // Sinon retourner un flashMessage d'erreur
            $this->addFlash('error', "Personne inexistante");
        }
        // Rediriger vers la liste des personnes après la suppression
        return $this->redirectToRoute('personne.list.alls');
    }

    #[Route('/update/{id}/{name}/{firstname}/{age}' ,name:'personne.update')]
    public function updatePersonne(Personne $personne = null,ManagerRegistry $doctrine  , $name,$firstname,$age ): RedirectResponse {
        // Verifier que  la personne à mettre à jours existe
        if ($personne) {
            // Si la personne existe => mettre à jour notre personne + un message de succes
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);

            // Recuperartion de manager
            $manager = $doctrine->getManager();
             //Persistence
             $manager -> persist($personne);
            // Exécuter la transaction
            $manager->flush();
           //message de succes
            $this->addFlash('success', "La personne a été mis à jours avec succès");
        } else {
            // Sinon => Declancher un message  d'erreur
            $this->addFlash('error', "Personne inexistante");
        }
        // Rediriger vers la liste des personnes après la suppression
        return $this->redirectToRoute('personne.list.alls');
    }

}
