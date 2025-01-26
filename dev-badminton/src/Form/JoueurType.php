<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Enum\Classement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $joueur = $options['data'] ?? null;

        $builder
            ->add('nom')
            ->add('prenom')
            ->add('numreo_licence')
            ->add('classement_simple', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(fn($c) => $c->value, Classement::cases()),
                    Classement::cases()
                ),
                'choice_label' => fn($choice) => $choice->value,
            ])
            ->add('cpph_simple')
            ->add('classement_double', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(fn($c) => $c->value, Classement::cases()),
                    Classement::cases()
                ),
                'choice_label' => fn($choice) => $choice->value,
            ])
            ->add('cpph_double')
            ->add('classement_mixtes', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(fn($c) => $c->value, Classement::cases()),
                    Classement::cases()
                ),
                'choice_label' => fn($choice) => $choice->value,
            ])
            ->add('cpph_mixtes');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}