<?php

namespace App\Service;

use App\Entity\Club;
use App\Entity\Equipe;
use App\Entity\Capitaine;
use App\Entity\Joueur;
use App\Entity\User;
use App\Form\JoueurType;
use App\Form\EquipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;

class EquipeService
{
    private $entityManager;
    private $formFactory;
    private $router;
    private $classementService;

    private $security;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, RouterInterface $router, ClassementService $classementService, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->classementService = $classementService;
        $this->security = $security;
    }

    public function createJoueurForEquipe($request)
    {
        $joueur = new Joueur();
        $form = $this->formFactory->create(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipes = $joueur->getEquipes(); // Récupérer la collection d'équipes

            foreach ($equipes as $equipe) {
                $equipe->addJoueur($joueur);
            }
            $this->entityManager->persist($joueur);
            $this->entityManager->flush();
            return ['redirect' => $this->router->generate('app_joueur_index')];
        }
        return [
            'joueur' => $joueur,
            'form' => $form,
        ];
    }

    public function createEquipe(Request $request)
    {
        $equipe = new Equipe();
        $form = $this->formFactory->create(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($equipe->getJoueurs() as $joueur) {
                $joueur->addEquipe($equipe);
                $this->entityManager->persist($joueur);
            }
            $score = $this->classementService->calculateEquipeValue($equipe);
            $this->entityManager->persist($equipe);
            $this->entityManager->flush();


            return ['redirect' => $this->router->generate('app_equipe_index')];
        }

        return [
            'equipe' => $equipe,
            'form' => $form,
        ];
    }

    public function editEquipe(Request $request, Equipe $equipe)
    {
        $form = $this->formFactory->create(EquipeType::class, $equipe, [
            'is_edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->classementService->calculateEquipeValue($equipe);
            $this->entityManager->flush();

            return ['redirect' => $this->router->generate('app_equipe_index')];
        }

        return [
            'equipe' => $equipe,
            'form' => $form->createView(),
            'is_edit' => true,
        ];
    }


    public function getEquipesForUser($user): array
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN')) {
            // Retourne toutes les équipes pour les administrateurs
            return $this->entityManager->getRepository(Equipe::class)->findAll();
        }

        if ($this->security->isGranted('ROLE_CLUB')) {
            // Retourne les équipes du club de l'utilisateur
            $club = $this->entityManager->getRepository(User::class)->findClubByUser($user);
            if ($club) {
                return $this->entityManager->getRepository(Equipe::class)->findBy(['club' => $club]);
            }
        }

        // Aucun accès pour les autres rôles
        return [];
    }
}