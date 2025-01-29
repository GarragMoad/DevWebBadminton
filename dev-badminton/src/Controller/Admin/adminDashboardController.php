<?php

namespace App\Controller\Admin;

use App\Service\ClassementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\EquipeRepository;






class adminDashboardController extends AbstractController
{
    
    public function __construct(private EquipeRepository $equipeRepository, private EntityManagerInterface $entityManager , private Security $security,
                                private ClassementService $classementService, private PaginatorInterface $paginator)
    {
       
    }


    #[Route('/superAdmin', name: 'superAdmin')]
    public function index(Request $request): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $equipes = $this->equipeRepository->findAll();
        $equipes = $this->classementService->sortEquipes($equipes);

        $pagination = $this->paginator->paginate(
            $equipes,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('superAdmin/dashboard.html.twig', [
            'pagination' => $pagination,
        ]);
    }

}
