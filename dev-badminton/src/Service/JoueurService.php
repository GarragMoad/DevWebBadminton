<?php
// src/Service/JoueurService.php

namespace App\Service;

use App\Entity\Club;
use App\Entity\Equipe;
use App\Entity\Joueur;
use Doctrine\ORM\EntityManagerInterface;

class JoueurService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
}