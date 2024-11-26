<?php
// src/Service/CapitaineService.php

namespace App\Service;

use App\Entity\Club;
use App\Entity\Equipe;
use Doctrine\ORM\EntityManagerInterface;

class CapitaineService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCapitaineFromUser($user): ?array
    {
        // Récupérer le club associé à l'utilisateur
        $club = $this->entityManager->getRepository(Club::class)->findOneBy(['nom' => explode('@', $user->getEmail())[0]]);
        
        if ($club) {
            // Récupérer les équipes associées au club
            $equipes = $this->entityManager->getRepository(Equipe::class)->findBy(['club' => $club]);
            
            // Récupérer les capitaines des équipes
            $capitaines = [];
            foreach ($equipes as $equipe) {
                $capitaines[] = $equipe->getCapitaine();
            }
            
            return $capitaines;
        }
        
        return null;
    }
}