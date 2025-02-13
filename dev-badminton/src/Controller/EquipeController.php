<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use App\Service\EquipeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ClubService;


#[Route('/equipe')]
final class EquipeController extends AbstractController
{
        #[Route(name: 'app_equipe_index', methods: ['GET'])]
        public function index(EquipeRepository $equipeRepository, EquipeService $equipeService , Request $request): Response
        {
            $equipesPaginated = $equipeService->getPaginatedEquipe($request);
            return $this->render('equipe/index.html.twig', [
                'pagination' => $equipesPaginated,
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
                'is_edit' => false,
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
    public function edit(Request $request, Equipe $equipe, EquipeService $equipeService): Response
    {
        $formViews = $equipeService->editEquipe($request, $equipe);

        if (isset($formViews['redirect'])) {
            return $this->redirect($formViews['redirect']);
        }

        return $this->render('equipe/edit.html.twig', [
            'equipe' => $formViews['equipe'],
            'form' => $formViews['form'],
            'is_edit' => $formViews['is_edit'],
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