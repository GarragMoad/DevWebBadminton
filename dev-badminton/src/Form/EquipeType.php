<?php

namespace App\Form;

use App\Entity\capitaine;
use App\Entity\Club;
use App\Entity\Equipe;
use App\Entity\joueur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_equipe')
            ->add('numero_equipe')
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choice_label' => function(Club $club) {
                    return $club->getNom();
                },
            ])
            ->add('capitaine', EntityType::class, [
                'class' => capitaine::class,
                'choice_label' => function(capitaine $capitaine) {
                    return $capitaine->getPrenom() . ' ' . $capitaine->getNom();
                },
            ])
            ->add('joueurs', EntityType::class, [
                'class' => joueur::class,
                'choice_label' => function(joueur $joueur) {
                    return $joueur->getPrenom() . ' ' . $joueur->getNom();
                },
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
