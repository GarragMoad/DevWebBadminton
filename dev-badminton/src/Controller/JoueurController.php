<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use App\Service\ClubService;
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

    public function __construct(JoueurEquipeService $jouerequipeService , ClubService $clubService)
    {
        $this->jouerequipeService = $jouerequipeService;
        $this->clubService = $clubService;
    }

    #[Route(name: 'app_joueur_index', methods: ['GET'])]
    public function index(JoueurRepository $joueurRepository): Response
    {
        $joueurs = [];
        if ($this->isGranted('ROLE_CLUB')){
            $user = $this->getUser();
            $club = $this->clubService->getClubFromUser($user);
            $joueurs = $joueurRepository->findJoueursByClub($club);
        }
        if($this->isGranted('ROLE_ADMIN')){
            $joueurs = $joueurRepository->findAll();
        }
        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurs,
        ]);
    }

    #[Route('/new', name: 'app_joueur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
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

        if ($form->isSubmitted() && $form->isValid()) {
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
