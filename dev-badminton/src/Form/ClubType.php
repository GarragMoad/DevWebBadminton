<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('sigle')
            ->add('gymnase')
            ->add('adresse')
            ->add('receptions', CollectionType::class, [
                'entry_type' => ReceptionType::class, // Utilise le form type de Reception
                'entry_options' => ['label' => false],
                'allow_add' => true,  // Permet d'ajouter une réception
                'by_reference' => false, // Important pour forcer l'ajout dans la relation OneToMany
                'label' => 'Réception',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
