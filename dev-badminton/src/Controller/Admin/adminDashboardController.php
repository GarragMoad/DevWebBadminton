<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ClubService;
use App\Entity\Club;
use App\Form\ClubType;
use Doctrine\ORM\EntityManagerInterface;




class adminDashboardController extends AbstractDashboardController
{
    public function __construct(private  ClubService  $clubService, private EntityManagerInterface $entityManager) {}


    #[Route('/admin/club', name: 'getClubs', methods: ['GET'])]
    public function index(): Response
    {
        
        $clubs = $this->clubService->getAllClubs();
        return $this->json($clubs);

        // return $this->render('admin/dashboard.html.twig', [
        //     'clubs' => $clubs,
        // ]);
    }

    #[Route('/admin/club/new', name: 'newClub', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $club = new Club();
        // Créer le formulaire pour le club
        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                // Persistons d'abord le club
                // $debugForm = $form->getData();
                // dd($debugForm);
                $this->entityManager->persist($club);
                $this->entityManager->flush();

                return $this->redirectToRoute('getClubs');
        }
        return $this->render('pages/newClub.html.twig', [
            'form' => $form,
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
        // yield MenuItem::linkToCrud('Clubs', 'fas fa-list', Club::class);
        // yield MenuItem::linkToCrud('Receptions', 'fas fa-list', Reception::class);
        // yield MenuItem::linkToCrud('Equipes', 'fas fa-list', Equipe::class);
    }
}
