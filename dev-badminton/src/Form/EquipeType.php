<?php
// src/Form/EquipeType.php


namespace App\Form;

use App\Entity\Club;
use App\Entity\Equipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
        $builder
            ->add('nom_equipe')
            ->add('numero_equipe')
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choices' => $this->clubService->getClubForForm($this->security->getUser()),
                'choice_label' => 'nom',
            ])
            ->add('current_joueur_index', HiddenType::class, [
                'mapped' => false,
                'data' => 0, // Indice du joueur en cours
            ])
            ->add('joueurs', CollectionType::class, [
                'entry_type' => JoueurType::class,
                'entry_options' => ['label' => false],
                'allow_add' => false,
                'allow_delete' => false,
                'by_reference' => false,
            ])
            ->add('is_edit', HiddenType::class, [
                'mapped' => false,
                'data' => $options['is_edit'] ?? false,
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
            'is_edit' => false,
        ]);
    }
}