<?php
// src/Service/CapitaineService.php

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Capitaine;
use App\Entity\Equipe;

class   CapitaineService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager , private PaginatorInterface $paginator,
    private Security $security, private ClubService $clubService)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Récupère les capitaines paginés selon les rôles de l'utilisateur.
     */
    public function getPaginatedCapitaines(Request $request)
    {
        $user = $this->security->getUser();

        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN')) {
            // Admins can see all captains
            $queryBuilder = $this->entityManager->getRepository(Capitaine::class)->createQueryBuilder('c')
                ->orderBy('c.nom', 'ASC');
            $query = $queryBuilder->getQuery();
            return $this->paginator->paginate($query, $request->query->getInt('page', 1), 10);
        } elseif ($this->security->isGranted('ROLE_CLUB')) {
            // Clubs can only see captains from their teams
            $capitaines = $this->getCapitainesFromUser($user);
            if ($capitaines) {
                return $this->paginator->paginate($capitaines, $request->query->getInt('page', 1), 10);
            } else {
                return $this->paginator->paginate([], $request->query->getInt('page', 1), 10);
            }
        } else {
            return $this->paginator->paginate([], $request->query->getInt('page', 1), 10);
        }
    }

    private function getCapitainesFromUser($user): ?array
    {
        // Récupérer le club associé à l'utilisateur
        $club = $this->clubService->getClubFromUser($user);

        if ($club) {
            // Récupérer les équipes associées au club
            $equipes = $this->entityManager->getRepository(Equipe::class)->findBy(['club' => $club]);
            // Récupérer les capitaines des équipes
            $capitaines = [];
            foreach ($equipes as $equipe) {
                $capitaine = $equipe->getCapitaine();
                if ($capitaine) {
                    $capitaines[] = $capitaine;
                }
            }

            return $capitaines;
        }

        return null;
    }


}