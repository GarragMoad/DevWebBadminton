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
        $club = $this->entityManager->getRepository(Club::class)->findOneBy(['nom' => explode('@', $user->getEmail())[0]]);
        
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
            // Admins peuvent voir tous les joueurs
            $query = $this->joueurRepository->createQueryBuilder('j')
                ->orderBy('j.nom', 'ASC')
                ->getQuery();
        } elseif ($this->security->isGranted('ROLE_CLUB')) {
            // Clubs ne voient que les joueurs de leurs équipes
            $club = $this->clubService->getClubFromUser($user);
            $query = $this->joueurRepository->createQueryBuilder('j')
                ->leftJoin('j.equipe', 'e')
                ->leftJoin('e.club', 'c')
                ->where('c.id = :clubId')
                ->setParameter('clubId', $club->getId())
                ->orderBy('j.nom', 'ASC')
                ->getQuery();
        } else {
            // Aucun joueur si non autorisé
            $query = null;
        }

        // Si aucune requête n'est disponible (utilisateur non autorisé), retourner une pagination vide
        if (!$query) {
            return $this->paginator->paginate([], 1, 10);
        }

        // Retourner les joueurs paginés
        return $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle (GET ?page=1)
            10 // Nombre d'éléments par page
        );
    }
}