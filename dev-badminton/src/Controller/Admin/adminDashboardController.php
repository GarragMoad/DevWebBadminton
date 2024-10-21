<?php

namespace App\Controller\Admin;

use App\Controller\ClubController;
use App\Form\ClubToReceptionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use App\Entity\Reception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ClubService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Club;
use App\Entity\Equipe;
use App\Form\ReceptionType;



class adminDashboardController extends AbstractDashboardController
{
    public function __construct(private  ClubService  $clubService, private EntityManagerInterface $entityManager) {}


    #[Route('/admin', name: 'getClubs', methods: ['GET'])]
    public function index(): Response
    {
        
         $clubs = $this->clubService->getAllClubs();
        return $this->render('admin/dashboard.html.twig', [
            'clubs' => $clubs,
        ]);
    }

    #[Route('/admin/club/new', name: 'newClub', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $formViews= $this->clubService->createClub($request);
        return $this->render('club/new.html.twig', [
            'formClub' => $formViews['clubForm'], 'ClubToReceptionForm' => $formViews['clubToReceptionForm']
        ]);
    }



    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dev Badminton');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Clubs', 'fas fa-list', ClubController::class);
        // yield MenuItem::linkToCrud('Receptions', 'fas fa-list', Reception::class);
        //  yield MenuItem::linkToCrud('Equipes', 'fas fa-list', Equipe::class);
    }
}
