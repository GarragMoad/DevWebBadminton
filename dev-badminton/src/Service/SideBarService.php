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

    public function __construct(Security $security, RouterInterface $router, ClubRepository $clubRepository, ClubService $clubService)
    {
        $this->security = $security;
        $this->router = $router;
        $this->clubRepository = $clubRepository;
        $this->clubService = $clubService;
    }

    public function getMenuItems(): array
    {
        $menuItems = [];

        // Menu item for Dashboard
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $menuItems[] = [
                'label' => 'Dashboard',
                'icon' => 'fa-solid fa-tachometer-alt',
                'url' => $this->router->generate('superAdmin'),
            ];
        }

        // Menu for administrators
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $menuItems[] = [
                'label' => 'Admins',
                'icon' => 'fa-solid fa-user-shield',
                'url' => $this->router->generate('app_user_index'),
            ];
        }

        // Menu for users with the role "ROLE_CLUB"
        if ($this->security->isGranted('ROLE_CLUB')) {
            $user = $this->security->getUser();
            $club = $this->clubService->getClubFromUser($user);
            if ($club) {
                $this->clubId = $club->getId();
            }
        }

        if ($this->clubId) {
            $menuItems[] = [
                'label' => 'Club',
                'icon' => 'fa-solid fa-building',
                'url' => $this->router->generate('app_club_show', ['id' => $this->clubId]),
            ];
        } else {
            $menuItems[] = [
                'label' => 'Clubs',
                'icon' => 'fa-solid fa-list',
                'url' => $this->router->generate('app_club_index'),
            ];
        }

        $menuItems[] = [
            'label' => 'Equipes',
            'icon' => 'fa-solid fa-users',
            'url' => $this->router->generate('app_equipe_index'),
        ];

        $menuItems[] = [
            'label' => 'Joueurs',
            'icon' => 'fa-solid fa-user',
            'url' => $this->router->generate('app_joueur_index'),
        ];

        $menuItems[] = [
            'label' => 'Capitaines',
            'icon' => 'fa-solid fa-user-tie',
            'url' => $this->router->generate('app_capitaine_index'),
        ];

        $menuItems[] = [
            'label' => 'Reception',
            'icon' => 'fa-solid fa-envelope',
            'url' => $this->router->generate('app_reception_index'),
        ];

        $menuItems[] = [
            'label' => 'Type Reception',
            'icon' => 'fa-solid fa-envelope-open-text',
            'url' => $this->router->generate('app_type_reception_index'),
        ];

        $menuItems[] = [
            'label' => 'Se déconnecter',
            'icon' => 'fa-solid fa-sign-out-alt',
            'url' => $this->router->generate('logout'),
        ];

        return $menuItems;
    }
}