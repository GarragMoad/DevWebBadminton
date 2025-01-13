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
use App\Repository\EquipeRepository;





class adminDashboardController extends AbstractDashboardController
{
    
    public function __construct(private EquipeRepository $equipeRepository, private EntityManagerInterface $entityManager , private Security $security)
    {
       
    }


    #[Route('/superAdmin', name: 'superAdmin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $equipes = $this->equipeRepository->findAll();
        // Calculate scores for each team
        foreach ($equipes as $equipe) {
            $equipe->calculateScore();
        }

        usort($equipes, function($a, $b) {
            return $b->getScore() <=> $a->getScore();
        });

        return $this->render('superAdmin/dashboard.html.twig', [
            'equipes' => $equipes,
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
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            yield MenuItem::linkToRoute('Users', 'fas fa-list', 'app_user_index');
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToRoute('Clubs', 'fas fa-list', 'app_club_index');
        }
        yield MenuItem::linkToRoute('Equipes', 'fas fa-list', 'app_equipe_index');
        yield MenuItem::linkToRoute('Joueurs', 'fas fa-list', 'app_joueur_index');
        yield MenuItem::linkToRoute('Capitaines', 'fas fa-list', 'app_capitaine_index');
        yield MenuItem::linkToRoute('Reception', 'fas fa-list', 'app_reception_index');
        yield MenuItem::linkToRoute('TypeReception', 'fas fa-list', 'app_type_reception_index');
    }

}
