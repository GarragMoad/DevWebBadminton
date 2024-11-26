<?php
// src/Form/EquipeType.php

namespace App\Form;

use App\Entity\Capitaine;
use App\Entity\Club;
use App\Entity\Equipe;
use App\Form\CapitaineType;
use App\Service\ClubService;
use App\Service\CapitaineService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ClubRepository;
use App\Repository\CapitaineRepository;

class EquipeType extends AbstractType
{
    private $security;
    private $clubRepository;
    private $capitaineRepository;
    private $clubService;
    private $capitaineService;

    public function __construct(Security $security, ClubRepository $clubRepository, CapitaineRepository $capitaineRepository, ClubService $clubService, CapitaineService $capitaineService)
    {
        $this->security = $security;
        $this->clubRepository = $clubRepository;
        $this->capitaineRepository = $capitaineRepository;
        $this->clubService = $clubService;
        $this->capitaineService = $capitaineService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('nom_equipe')
            ->add('numero_equipe')
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choices' => $this->getClubsForUser($user),
                'choice_label' => function(Club $club) {
                    return $club->getNom();
                }
            ])
            ->add('capitaine_choice', ChoiceType::class, [
                'choices' => [
                    'Choisir un capitaine existant' => 'existing',
                    'Créer un nouveau capitaine' => 'new',
                ],
                'mapped' => false,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('capitaine', EntityType::class, [
                'class' => Capitaine::class,
                'choices' => $this->capitaineService->getCapitaineFromUser($user),
                'choice_label' => function(Capitaine $capitaine) {
                    return $capitaine->getNom();
                },
                'required' => false,
            ])
            ->add('new_capitaine', CapitaineType::class, [
                'mapped' => false,
                'required' => false,
            ]);

        // Ajouter des champs pour les joueurs si nécessaire
        // $builder->add('joueurs', CollectionType::class, [
        //     'entry_type' => JoueurType::class,
        //     'entry_options' => ['label' => false],
        //     'allow_add' => true,
        //     'allow_delete' => true,
        //     'by_reference' => false,
        // ]);
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
        ]);
    }
}