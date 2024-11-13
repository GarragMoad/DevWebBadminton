<?php
// src/Form/EquipeType.php

namespace App\Form;

use App\Entity\Capitaine;
use App\Entity\Club;
use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Service\ClubService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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

    public function __construct(Security $security, ClubRepository $clubRepository, CapitaineRepository $capitaineRepository, JoueurRepository $joueurRepository, ClubService $clubService)
    {
        $this->security = $security;
        $this->clubRepository = $clubRepository;
        $this->capitaineRepository = $capitaineRepository;
        $this->joueurRepository = $joueurRepository;
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
                'choice_label' => function(Club $club) {
                    return $club->getNom();
                }
            ])
            ->add('capitaine', EntityType::class, [
                'class' => Capitaine::class,
                'choices' => $this->getCapitainesForUser($user),
                'choice_label' => function(Capitaine $capitaine) {
                    return $capitaine->getPrenom() . ' ' . $capitaine->getNom();
                },
            ])
            ->add('joueurs', EntityType::class, [
                'class' => Joueur::class,
                'choices' => $this->getJoueursForUser($user),
                'choice_label' => function(Joueur $joueur) {
                    return $joueur->getPrenom() . ' ' . $joueur->getNom();
                },
                'multiple' => true,
            ])
        ;
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

    private function getCapitainesForUser($user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->capitaineRepository->findAll();
        } else  if($this->security->isGranted('ROLE_CLUB')) {
            //return $this->capitaineRepository->findBy(['club' =>$this->clubService->getClubFromUser($user) ]);
            return $this->capitaineRepository->findAll();
        }
    }

    private function getJoueursForUser($user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->joueurRepository->findAll();
        } else {
            return $this->joueurRepository->findAll();
            //return $this->joueurRepository->findBy(['club' => $this->clubService->getClubFromUser($user)]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}