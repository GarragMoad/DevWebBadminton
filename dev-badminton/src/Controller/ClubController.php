<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use App\Service\ClubService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;


#[Route('/club')]
final class ClubController extends AbstractController
{
    private $clubService;

    private $adminUrlGenerator;
    public function __construct(ClubService $clubService, AdminUrlGenerator $adminUrlGenerator )
    {
        $this->clubService = $clubService;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    
    #[Route(name: 'app_club_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {
        
        //$menuItems = $this->get('easyadmin.config.menu_items');

        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_club_new', methods: ['GET', 'POST'])]
    public function new(Request $request ): Response
    {
        $formViews= $this->clubService->createClub($request);
        
        if (isset($formViews['redirect'])) {
            return $this->redirect($formViews['redirect']);
        }
        return $this->render('club/new.html.twig', [
            'formClub' => $formViews['clubForm'], 'ClubToReceptionForm' => $formViews['clubToReceptionForm']
            
        ]);
    }

    #[Route('/{id}', name: 'app_club_show', methods: ['GET'])]
    public function show(Club $club): Response
    {
        return $this->render('club/show.html.twig', [
            'club' => $club,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        $formViews= $this->clubService->createClub($request);
        
        if (isset($formViews['redirect'])) {
            return $this->redirect($formViews['redirect']);
        }
        return $this->render('club/edit.html.twig', [
            'formClub' => $formViews['clubForm'], 'ClubToReceptionForm' => $formViews['clubToReceptionForm'] , 'club' => $club
        ]);
    }

    #[Route('/{id}', name: 'app_club_delete', methods: ['POST'])]
    public function delete(Request $request, Club $club): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->getPayload()->getString('_token'))) {
            $this->clubService->deleteClub($club);
        }

        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }
}