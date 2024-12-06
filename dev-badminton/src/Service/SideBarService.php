<?php 
// src/Service/SidebarMenuBuilder.php
namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\ClubRepository;

class SideBarService
{
    private Security $security;
    private RouterInterface $router;

    private ClubRepository $clubRepository;

    private $clubId;

    private $clubService;

    public function __construct(Security $security, RouterInterface $router , ClubRepository $clubRepository, ClubService $clubService)
    {
        $this->security = $security;
        $this->router = $router;
        $this->clubRepository = $clubRepository;
        $this->clubService = $clubService;
    }

    public function getMenuItems(): array
    {
        $menuItems = [];

        // Menu item pour Dashboard
        if ($this->security->isGranted('ROLE_ADMIN')){
            $menuItems[] = [
                'label' => 'Dashboard',
                'icon' => 'fa fa-home',
                'url' => $this->router->generate('superAdmin'), // Route de votre dashboard
            ];
    
        }
       
        // Menu pour les administrateurs
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $menuItems[] = [
                'label' => 'Admins',
                'icon' => 'fas fa-list',
                'url' => $this->router->generate('app_user_index'),
            ];
        }

        // Menu pour les utilisateurs avec le rôle "ROLE_ADMIN"
        if ($this->security->isGranted('ROLE_CLUB')) {
            $user = $this->security->getUser();
                $club = $this->clubService->getClubFromUser($user);
                if ($club) {
                    $this->clubId = $club->getId();
                }

            }
        if($this->clubId){
            $menuItems[] = [
                'label' => 'Club',
                'icon' => 'fa fa-home',
                'url' => $this->router->generate('app_club_show', ['id' => $this->clubId]),
            ];
        }
        else{
            $menuItems[] = [
                'label' => 'Clubs',
                'icon' => 'fas fa-list',
                'url' => $this->router->generate('app_club_index'),
            ];
        }


        $menuItems[] = [
            'label' => 'Equipes',
            'icon' => 'fas fa-list',
            'url' => $this->router->generate('app_equipe_index'),
        ];

        $menuItems[] = [
            'label' => 'Joueurs',
            'icon' => 'fas fa-list',
            'url' => $this->router->generate('app_joueur_index'),
        ];

        $menuItems[] = [
            'label' => 'Capitaines',
            'icon' => 'fas fa-list',
            'url' => $this->router->generate('app_capitaine_index'),
        ];

        $menuItems[] = [
            'label' => 'Reception',
            'icon' => 'fas fa-list',
            'url' => $this->router->generate('app_reception_index'),
        ];

        $menuItems[] = [
            'label' => 'Type Reception',
            'icon' => 'fas fa-list',
            'url' => $this->router->generate('app_type_reception_index'),
        ];

        // Ajoutez d'autres items au besoin

        return $menuItems;
    }
}
