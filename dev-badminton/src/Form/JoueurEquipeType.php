<?php

namespace App\Form;
use App\Entity\Joueur;
use App\Entity\Equipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class JoueurEquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom_equipe',
                'label' => 'Equipe',
                'placeholder' => 'Sélectionnez une équipe',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Pas de classe de données spécifique
        ]);
    }
}