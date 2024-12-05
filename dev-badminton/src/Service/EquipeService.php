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

    public function createEquipe($request){
        $equipe = new Equipe();
        $form = $this->formFactory->create(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du champ non mappé
            $newCapitaineData = $form->get('new_capitaine')->getData();
            // Récupérer les données du champ non mappé pour les nouveaux joueurs
            $newJoueurData = $form->get('new_joueurs')->getData();
            if($newJoueurData){
                    $joueur = new Joueur();
                    $joueur->setNom($newJoueurData->getNom());
                    $joueur->setPrenom($newJoueurData->getPrenom());
                    $joueur->setNumreoLicence($newJoueurData->getNumreoLicence());
                    $joueur->setClassementSimple($newJoueurData->getClassementSimple());
                    $joueur->setCpphSimple($newJoueurData->getCpphSimple());
                    $joueur->setClassementDouble($newJoueurData->getClassementDouble());
                    $joueur->setCpphDouble($newJoueurData->getCpphDouble());
                    $joueur->setClassementMixtes($newJoueurData->getClassementMixtes());
                    $joueur->setCpphMixtes($newJoueurData->getCpphMixtes());
                    $this->entityManager->persist($joueur);
                    $equipe->addJoueur($joueur);
                
            }
            

            // Créer un nouveau capitaine si les données sont présentes
            if ($newCapitaineData) {
                $capitaine = new Capitaine();
                $capitaine->setNom($newCapitaineData->getNom());
                $capitaine->setPrenom($newCapitaineData->getPrenom());
                $capitaine->setMail($newCapitaineData->getMail());
                $capitaine->setTelephone($newCapitaineData->getTelephone());
                $this->entityManager->persist($capitaine);
                $equipe->setCapitaine($capitaine);
            } else if(! $newCapitaineData) {
                // Récupérer le capitaine existant
                $capitaine = $form->get('capitaine')->getData();
                $equipe->setCapitaine($capitaine);
            }
            foreach ($equipe->getJoueurs() as $joueur) {
                $equipe->addJoueur($joueur);
                $this->entityManager->persist($joueur);
            }
            $this->entityManager->persist($equipe);
            $this->entityManager->flush();
            return ['redirect' => $this->router->generate('app_equipe_index')];
        }

        return [
            'equipe' => $equipe,
            'form' => $form,
        ];
    }

    public function getEquipesFromUser($user): ?array
    {
        $club = $this->entityManager->getRepository(User::class)->findClubByUser($user);
        if ($club) {
            return $this->entityManager->getRepository(Equipe::class)->findBy(['club' => $club]);
        }
        return null;
    }


}
