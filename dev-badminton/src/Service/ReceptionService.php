<?php

namespace App\Service;


use App\Entity\Reception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class ReceptionService
{

    public function __construct(private Security $security , private ClubService $clubService
                                , private EntityManagerInterface $entityManager, private PaginatorInterface $paginator)
    {
    }
    /**
     * Récupère les récéptions paginés selon les rôles de l'utilisateur.
     */
    public function getPaginatedReceptions(Request $request)
    {
        $user = $this->security->getUser();

        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN')) {
            // Admins can see all captains
            $queryBuilder = $this->entityManager->getRepository(Reception::class)->createQueryBuilder('r')
                ->orderBy('r.id', 'ASC');
            $query = $queryBuilder->getQuery();
            return $this->paginator->paginate($query, $request->query->getInt('page', 1), 10);
        } elseif ($this->security->isGranted('ROLE_CLUB')) {
            // Clubs can only see captains from their teams
            $club = $this->clubService->getClubFromUser($user);
            if ($club) {
                $receptions= $this->entityManager->getRepository(Reception::class)->findBy(['club' => $club]);
                return $this->paginator->paginate($receptions, $request->query->getInt('page', 1), 10);
            } else {
                return $this->paginator->paginate([], $request->query->getInt('page', 1), 10);
            }
        } else {
            return $this->paginator->paginate([], $request->query->getInt('page', 1), 10);
        }
    }


}