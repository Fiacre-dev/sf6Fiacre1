<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonnesEvent;
use App\Form\PersonneType;
use App\service\Helpers;
use App\service\MailerService;
use App\service\PdfService;
use App\service\UploaderService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[
    Route('personne'),
    IsGranted('ROLE_USER')
]
class PersonneController extends AbstractController
{

    public function __construct(
        private LoggerInterface $logger,
        private Helpers $helper,
        private EventDispatcherInterface $dispatcher
    )
    { }

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
    #[Route('/pdf/{id}', name:'personne.pdf')]
    public function generatePdfPersonne(Personne $personne=null,PdfService $pdf ){
        $html=$this->render('personne/detail.html.twig',['personne'=>$personne]);
        $pdf->showPdfFile($html);
    }
    //La liste
 #[Route('/alls/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonnesByAgeInterval($ageMin, $ageMax);

        return $this->render('personne/index', [
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
    #[
        Route('/alls/{page?1}/{nbre?12}', name: 'personne.list.alls'),
        IsGranted("ROLE_USER")
    ]
    public function indexalls(ManagerRegistry $doctrine, $page, $nbre): Response
    {

//       echo ($this->helper ->sayCc());

        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]);
        
        $nbrepage = ceil($nbPersonne / $nbre);
 
        $personnes = $repository->findBy([], [], $nbre, ($page - 1) * $nbre);

        $listAllPersonneEvent = new ListAllPersonnesEvent(count($personnes));
        $this->dispatcher->dispatch($listAllPersonneEvent, ListAllPersonnesEvent::LIST_ALL_PERSONNE_EVENT);

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbrePage' => $nbrepage,
            'page' => $page,
            'nbre' => $nbre,
        ]);
    }

    //Les detailles d'une personne
    #[Route('/{id<\d+>}', name: 'personne.detail'),
        IsGranted("ROLE_ADMIN")
    ]
    public function detail(Personne $personne = null): Response
    {
        if (!$personne) {
            $this->addFlash('error', "La personne n'existe pas");
            //return $this->redirectToRoute('personne.list');
            return $this->redirectToRoute('/');
        }

        return $this->render('personne/detail', ['personne' => $personne]);
    }

    //Ajout d'une personne
    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function addPersonne(
        Personne $personne = null,
        ManagerRegistry $doctrine,
        Request $request ,
        UploaderService $uploaderService,
        MailerService $mailer,
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $new=false;
        if(!$personne){
            $new=true;
            $personne = new Personne();
        }
        //$personne est l'image de notre formulaire
        $form = $this -> createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        //Mon formulaire va aller traiter la request
        // association de la request envoyer avec le formulaire
        $form -> handleRequest($request);
        //Est ce que le formulaire à ete soumis
        if($form->isSubmitted() && $form ->isValid()){
            //Si oui
            //on va ajourter l'objet personne dans la base de données
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $directory =  $this->getParameter('personne_directory');

                $personne->setImage($uploaderService->uploadFile($photo,$directory));
            }

            if($new){
                $message="à été ajouté avec succès";
                $personne ->setCreatedBy($this->getUser());
            }else{
                $message="à été mis à jour avec succès";
            }
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();

            if($new){
                //on a creer notre evenement
                $addPersonneEvent=new AddPersonneEvent($personne);
                //on va maintenant dispatcher cet evenement
                $this->dispatcher->dispatch($addPersonneEvent,AddPersonneEvent::ADD_PERSONNE_EVENT);

            }
            $this->addFlash('success', $personne->getName() . $message);


            //Afficher un message du succes

            //Si non rediriger vers la liste des personnes
            return $this->redirectToRoute('/');
        }else{
            //Si non
           // on affiche le formulaire
            return $this->render('personne/add-personne.html.twig', [
                'form'=>$form ->createView()
             ]);
        }

     }

    //Suppression d'une personnes
    #[
        Route('/delete/{id}' ,name:'personne.delete'),
        IsGranted('ROLE_ADMIN')
    ]
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
