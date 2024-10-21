<?php

namespace App\Repository;

use App\Entity\Jours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jours[]    findAll()
 * @method Jours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jours::class);
    }

    // Add your custom repository methods below
}