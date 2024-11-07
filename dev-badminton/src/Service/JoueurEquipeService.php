<?php

namespace App\Service;

use App\Entity\Joueur;
use App\Entity\Equipe;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\JoueurEquipeType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class JoueurEquipeService
{
    private $entityManager;
    private $formFactory;

    private $router;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory,RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function ajouterJoueurEquipe(Request $request, Joueur $joueur): array
{
    $form = $this->formFactory->create(JoueurEquipeType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $equipe = $data['equipe'];

        // Ajouter le joueur à l'équipe
        $equipe->addJoueur($joueur);

        // Persister les modifications
        $this->entityManager->persist($joueur);
        $this->entityManager->persist($equipe);
        $this->entityManager->flush();

        return ['redirect' => $this->router->generate('app_joueur_index')];
    }

    return ['form' => $form];
}
}