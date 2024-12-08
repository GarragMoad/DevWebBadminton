<?php
// src/Controller/Partial/MenuController.php
namespace App\Controller\Partial;


use App\Service\SideBarService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MenuController extends AbstractController
{
    private SideBarService $sideBarService;

    private $userService;

    public function __construct(SideBarService $sideBarService , UserService $userService)
    {
        $this->sideBarService = $sideBarService;
        $this->userService = $userService;
    }

    #[Route('/admin/menu', name: 'admin_menu')]
    public function index(): Response
    {
        return $this->render('partial/admin_menu.html.twig', [
            'menuItems' => $this->sideBarService->getMenuItems(),
        ]);
    }

    public function getUserEmail(): Response
    {
        $user = $this->getUser();
        $email = $user ? $this->userService->getUserEmail($user) : 'Guest';

        return new Response($email);
    }
}
