<?php
// src/Form/EquipeType.php

// src/Form/EquipeType.php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Equipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ClubRepository;
use App\Service\ClubService;

class EquipeType extends AbstractType
{
    private $security;
    private $clubRepository;
    private $clubService;

    public function __construct(Security $security, ClubRepository $clubRepository, ClubService $clubService)
    {
        $this->security = $security;
        $this->clubRepository = $clubRepository;
        $this->clubService = $clubService;
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
                'choice_label' => function (Club $club) {
                    return $club->getNom();
                }
            ])
            ->add('joueurs', CollectionType::class, [
                'entry_type' => JoueurType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__joueur__',
                'attr' => [
                    'class' => 'joueurs-collection',
                ],
            ]);
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