<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use App\Service\ClassementService;
use App\Service\ClubService;
use App\Service\EquipeService;
use App\Service\JoueurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\JoueurEquipeService;

#[Route('/joueur')]
final class JoueurController extends AbstractController
{
    private $jouerequipeService;
    private $clubService;

    private $equipeService;

    private $classementService;


    public function __construct(JoueurEquipeService $jouerequipeService , ClubService $clubService, EquipeService $equipeService ,
                                ClassementService $classementService, private JoueurService $joueurService)
    {
        $this->jouerequipeService = $jouerequipeService;
        $this->clubService = $clubService;
        $this->equipeService = $equipeService;
        $this->classementService = $classementService;
    }

    #[Route(name: 'app_joueur_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // Utilisation du service pour récupérer les joueurs paginés
        $paginatedPlayers = $this->joueurService->getPaginatedPlayers($request);
        return $this->render('joueur/index.html.twig', [
            'pagination' => $paginatedPlayers,
        ]);
    }

    #[Route('/new', name: 'app_joueur_new',methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
            $formViews= $this->equipeService->createJoueurForEquipe($request);
            
            if (isset($formViews['redirect'])) {
                return $this->redirect($formViews['redirect']);
            }
            return $this->render('joueur/new.html.twig', [
                'joueur' => $formViews['joueur'], 'form' => $formViews['form']
                
            ]);
        
    }

    #[Route('/{id}', name: 'app_joueur_show', methods: ['GET'])]
    public function show(Joueur $joueur): Response
    {
        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_joueur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);
        $equipes = $joueur->getEquipes();
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($equipes as $equipe) {
                $this->classementService->calculateEquipeValue($equipe);
                $this->classementService->calculateEquipeValueWithCpph($equipe);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_joueur_delete', methods: ['POST'])]
    public function delete(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/addJoueurEquipe/{id}', name: 'app_joueur_equipe', methods: ['GET', 'POST'])]
    public function addJoueurEquipe(Request $request , Joueur $joueur): Response{
        $form = $this->jouerequipeService->ajouterJoueurEquipe($request , $joueur);
        if (isset($form['redirect'])) {
            return $this->redirect($form['redirect']);
        }
        return $this->render('joueur/joueur_equipe.html.twig', [
            'form' => $form['form'],
            'joueur' => $joueur
        ]);

    }

}
