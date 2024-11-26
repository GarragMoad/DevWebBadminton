<?php

namespace App\Service;
use App\Entity\Club;
use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Form\JoueurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
class EquipeService
{
    private $entityManager;
    private $formFactory;

    private $router;
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory , RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function createJoueurForEquipe($request){
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
        return[
            'joueur' => $joueur,
            'form' => $form,
        ];
    }

    public function getEquipesFromUser($user): ?array
    {
        $club = $this->entityManager->getRepository(Club::class)->findOneBy(['nom' => explode('@', $user->getEmail())[0]]);
        if ($club) {
            return $this->entityManager->getRepository(Equipe::class)->findBy(['club' => $club]);
        }
        return null;
    }

    public function getCapitainesFromUser($user): ?array
    {
        $club = $this->entityManager->getRepository(Club::class)->findOneBy(['nom' => explode('@', $user->getEmail())[0]]);
        if ($club) {
            $equipes = $this->entityManager->getRepository(Equipe::class)->findBy(['club' => $club]);
            $capitaines = [];
            foreach ($equipes as $equipe) {
                $capitaines[] = $equipe->getCapitaine();
            }
            return $capitaines;
        }
        return null;
    }

}
