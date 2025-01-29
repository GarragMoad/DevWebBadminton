<?php
// src/Service/JoueurService.php

namespace App\Service;

use App\Entity\Club;
use App\Entity\Equipe;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class JoueurService
{
    private $entityManager;

    private $joueurRepository;
    private $paginator;

    public function __construct(EntityManagerInterface $entityManager, JoueurRepository $joueurRepository,
                                PaginatorInterface $paginator, private Security $security, private ClubService $clubService )
    {
        $this->entityManager = $entityManager;
        $this->joueurRepository = $joueurRepository;
        $this->paginator=$paginator;
    }

    public function getJoueursFromUser($user): ?array
    {
        // Récupérer le club associé à l'utilisateur
        $club = $this->clubService->getClubFromUser($user);
        
        if ($club) {
            // Récupérer les équipes associées au club
            $equipes = $this->entityManager->getRepository(Equipe::class)->findBy(['club' => $club]);
            // Récupérer les joueurs des équipes
            $joueurs = [];
            foreach ($equipes as $equipe) {
                foreach ($equipe->getJoueurs() as $joueur) {
                    $joueurs[] = $joueur;
                }
            }
            
            return $joueurs;
        }
        
        return null;
    }

    /**
     * Récupère les joueurs paginés selon les rôles de l'utilisateur.
     */


    public function getPaginatedPlayers(Request $request)
    {
        $user = $this->security->getUser();

        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN')) {
            // Admins can see all players
            $queryBuilder = $this->joueurRepository->createQueryBuilder('j')
                ->orderBy('j.nom', 'ASC');
            $query = $queryBuilder->getQuery();
            return $this->paginator->paginate($query, $request->query->getInt('page', 1), 10);
        } elseif ($this->security->isGranted('ROLE_CLUB')) {
            // Clubs can only see players from their teams
            $joueurs = $this->getJoueursFromUser($user);
            if ($joueurs) {
                return $this->paginator->paginate($joueurs, $request->query->getInt('page', 1), 10);
            } else {
                return $this->paginator->paginate([], $request->query->getInt('page', 1), 10);
            }
        } else {
            return $this->paginator->paginate([], $request->query->getInt('page', 1), 10);
        }
    }


}