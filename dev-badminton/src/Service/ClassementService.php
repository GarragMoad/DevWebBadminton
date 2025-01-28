<?php

namespace App\Service;

use App\Entity\Equipe;
use App\Enum\Classement;
use Doctrine\ORM\EntityManagerInterface;

class ClassementService
{
    private array $classementValues = [
        Classement::N1->value => 12,
        Classement::N2->value => 11,
        Classement::N3->value => 10,
        Classement::R4->value => 9,
        Classement::R5->value => 8,
        Classement::R6->value => 7,
        Classement::D7->value => 6,
        Classement::D8->value => 5,
        Classement::D9->value => 4,
        Classement::P10->value => 3,
        Classement::P11->value => 2,
        Classement::P12->value => 1,
        Classement::NC->value => 0,
    ];

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getClassementValue(Classement $classement): int
    {
        return $this->classementValues[$classement->value] ?? 0;
    }

    public function calculateEquipeValue(Equipe $equipe): int
    {
        $totalValue = 0;

        foreach ($equipe->getJoueurs() as $joueur) {
            $totalValue += $this->getClassementValue(Classement::from($joueur->getClassementSimple()->value));
            $totalValue += $this->getClassementValue(Classement::from($joueur->getClassementDouble()->value));
            $totalValue += $this->getClassementValue(Classement::from($joueur->getClassementMixtes()->value));
        }
        $equipe->setScore($totalValue);

        return $totalValue;
    }
}