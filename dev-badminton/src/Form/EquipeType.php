<?php
// src/Form/EquipeType.php

namespace App\Form;

use App\Entity\Capitaine;
use App\Entity\Club;
use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Service\ClubService;
use App\Service\CapitaineService;
use App\Service\JoueurService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ClubRepository;
use App\Repository\CapitaineRepository;
use App\Repository\JoueurRepository;

class EquipeType extends AbstractType
{
    private $security;
    private $clubRepository;
    private $capitaineRepository;
    private $joueurRepository;
    private $clubService;
    private $capitaineService;

    private $joueurService;

    public function __construct(Security $security, ClubRepository $clubRepository, CapitaineRepository $capitaineRepository, JoueurRepository $joueurRepository, ClubService $clubService, CapitaineService $capitaineService , JoueurService $joueurService)
    {
        $this->security = $security;
        $this->clubRepository = $clubRepository;
        $this->capitaineRepository = $capitaineRepository;
        $this->joueurRepository = $joueurRepository;
        $this->clubService = $clubService;
        $this->capitaineService = $capitaineService;
        $this->joueurService = $joueurService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $isEditMode = $options['is_edit']; // Option pour savoir si on est en mode édition

        $builder
            ->add('nom_equipe')
            ->add('numero_equipe')
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choices' => $this->getClubsForUser($user),
                'choice_label' => function (Club $club) {
                    return $club->getNom();
                }
            ]);


        if ($isEditMode) {
            $builder->add('joueurs', EntityType::class, [
                'class' => Joueur::class,
                'choices' => $this->joueurService->getJoueursFromUser($user),
                'choice_label' => function (Joueur $joueur) {
                    return $joueur->getNom();
                },
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ]);

        }
    }
    private function getClubsForUser($user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->clubRepository->findAll();
        } elseif ($this->security->isGranted('ROLE_CLUB')) {
            $club = $this->clubService->getClubFromUser($user);
            return $club ? [$club] : [];
        }
        return [];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
            'is_edit'=> false,
        ]);
    }
}