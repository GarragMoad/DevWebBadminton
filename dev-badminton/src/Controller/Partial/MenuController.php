<?php
// src/Controller/Partial/MenuController.php
namespace App\Controller\Partial;


use App\Service\SideBarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\adminDashboardController;

class MenuController extends AbstractController
{
    private SideBarService $sideBarService;

    public function __construct(SideBarService $sideBarService)
    {
        $this->sideBarService = $sideBarService;
    }

    #[Route('/admin/menu', name: 'admin_menu')]
    public function index(): Response
    {
        return $this->render('partial/admin_menu.html.twig', [
            'menuItems' => $this->sideBarService->getMenuItems(),
        ]);
    }
}
