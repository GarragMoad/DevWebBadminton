<?php

namespace App\Service;

use App\Entity\Club;
use App\Entity\Reception;
use App\Entity\Equipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ClubType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClubService
{
    private $entityManager;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }
    public function createClub(Request $request)       
    {
        // Création du club
        $club = new Club();
        // Créer le formulaire pour le club
        $form = $this->formFactory->create(ClubType::class, $club);

        $form->handleRequest($form);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Persistons d'abord le club
                $debugForm = $form->getData();
                dd($debugForm);
                $this->entityManager->persist($club);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                return new Response('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        

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