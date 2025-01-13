<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use App\Service\EquipeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ClubService;
use App\Entity\Joueur;

#[Route('/equipe')]
final class EquipeController extends AbstractController
{
        #[Route(name: 'app_equipe_index', methods: ['GET'])]
        public function index(EquipeRepository $equipeRepository, ClubService $clubService): Response
        {
            $equipes = $this->getEquipesForUser($equipeRepository, $clubService);    
            return $this->render('equipe/index.html.twig', [
                'equipes' => $equipes,
            ]);
        }
    
        #[Route('/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EquipeService $equipeService): Response
        {
           $formViews=$equipeService->createEquipe($request);

           if (isset($formViews['redirect'])) {
            return $this->redirect($formViews['redirect']);
        }
            return $this->render('equipe/new.html.twig', [
                'equipe' => $formViews['equipe'],
                'form' =>$formViews['form'],
            ]);
        }

        #[Route('/{id}', name: 'app_equipe_show', methods: ['GET'])]
        public function show(Equipe $equipe, ClubService $clubService): Response
        {

    
            return $this->render('equipe/show.html.twig', [
                'equipe' => $equipe,
                'joueurs' => $equipe->getJoueurs(),
                'club' => $equipe->getClub(),
                'capitaine' => $equipe->getCapitaine(),
            ]);
        }
    
        #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager, ClubService $clubService): Response
        {
    
            $form = $this->createForm(EquipeType::class, $equipe,[
                'is_edit' => true
            ]);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
    
                return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->render('equipe/edit.html.twig', [
                'equipe' => $equipe,
                'form' => $form->createView(),
            ]);
        }
    
        #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST'])]
        public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager, ClubService $clubService): Response
        {
    
            if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
                $entityManager->remove($equipe);
                $entityManager->flush();
            }
    
            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }
    
        private function getEquipesForUser(EquipeRepository $equipeRepository, ClubService $clubService): array
        {
            $user = $this->getUser();
            $equipes = [];
    
            if ($this->isGranted('ROLE_ADMIN')) {
                $equipes = $equipeRepository->findAll();
            } elseif ($this->isGranted('ROLE_CLUB')) {
                $club = $clubService->getClubFromUser($user);
                if ($club) {
                    $equipes = $equipeRepository->findBy(['club' => $club]);
                }
            }
    
            return $equipes;
        }
    
    }