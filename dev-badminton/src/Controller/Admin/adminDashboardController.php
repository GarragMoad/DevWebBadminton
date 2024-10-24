<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ClubService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;




class adminDashboardController extends AbstractDashboardController
{
    private $security;
    public function __construct(private  ClubService  $clubService, private EntityManagerInterface $entityManager ,Security $security) {
        $this->security = $security;
    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
         $clubs = $this->clubService->getAllClubs();
        return $this->render('admin/dashboard.html.twig', [
            'clubs' => $clubs,
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
        yield MenuItem::linkToRoute('Clubs', 'fas fa-list','app_club_index');
        yield MenuItem::linkToRoute('Equipes', 'fas fa-list', 'app_equipe_index');
        yield MenuItem::linkToRoute('Joueurs', 'fas fa-list', 'app_joueur_index');
        yield MenuItem::linkToRoute('Capitaines', 'fas fa-list', 'app_capitaine_index');
        yield MenuItem::linkToRoute('Reception', 'fas fa-list', 'app_reception_index');
    }
}
