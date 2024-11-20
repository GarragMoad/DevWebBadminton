<?php

namespace App\Repository;

use App\Entity\Joueur;
use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Joueur>
 */
class JoueurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    /**
     * @return Joueur[] Returns an array of Joueur objects associated with a specific Club
     */
    public function findJoueursByClub(Club $club)
    {
        return $this->createQueryBuilder('j')
            ->innerJoin('j.equipes', 'e')
            ->innerJoin('e.club', 'c')
            ->where('c = :club')
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Joueur[] Returns an array of Joueur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Joueur
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
