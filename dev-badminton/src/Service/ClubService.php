<?php

namespace App\Service;

use App\Entity\Club;
use App\Entity\Reception;
use App\Entity\Equipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClubType;
use App\Form\ClubToReceptionType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface; // Import du RouterInterface


class ClubService
{
    private $entityManager;
    private $formFactory;
    private $router;
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->formFactory = $formFactory;
    }
    public function createClub(Request $request): array 
    {   
        // Création du club 
        $club = new Club();
        $reception= new Reception();

        // Créer le formulaire pour le club
        $ClubForm = $this->formFactory->create(ClubType::class, $club);
        $ClubToReceptionForm= $this->formFactory->create(ClubToReceptionType::class, $reception);
        $ClubToReceptionForm->handleRequest($request);
        $ClubForm->handleRequest($request);
        

        if ($ClubForm->isSubmitted() && $ClubForm->isValid()) {
                // Persistons d'abord le club
                $this->entityManager->persist($club);
                $this->entityManager->flush();

                if($ClubToReceptionForm->isSubmitted() && $ClubToReceptionForm->isValid()){
                    $reception->setClub($club);
                    $this->entityManager->persist($reception);
                    $this->entityManager->flush(); 
                    return ['redirect' => $this->router->generate('app_club_index')];
                }
            }

       
        return [
            'clubForm' => $ClubForm,
            'clubToReceptionForm' => $ClubToReceptionForm,
        ];
        
    }


    public function getClub(int $id): ?Club
    {
        return $this->entityManager->getRepository(Club::class)->find($id);
    }

    public function getAllClubs(): array
    {
        return $this->entityManager->getRepository(Club::class)->findAll();
    }

    public function updateClub(Club $club): Club
    {
        $this->entityManager->flush();
        return $club;
    }

    public function deleteClub(Club $club): void
    {
        $this->entityManager->remove($club);
        $this->entityManager->flush();
    }

    public function addReceptionToClub(Club $club, Reception $reception): Club
    {
        $club->addReception($reception);
        $this->entityManager->flush();
        return $club;
    }

    public function removeReceptionFromClub(Club $club, Reception $reception): Club
    {
        $club->removeReception($reception);
        $this->entityManager->flush();
        return $club;
    }

    public function addEquipeToClub(Club $club, Equipe $equipe): Club
    {
        $club->addEquipe($equipe);
        $this->entityManager->flush();
        return $club;
    }

    public function removeEquipeFromClub(Club $club, Equipe $equipe): Club
    {
        $club->removeEquipe($equipe);
        $this->entityManager->flush();
        return $club;
    }
}