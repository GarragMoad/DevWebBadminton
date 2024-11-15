<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Jours;
use App\Entity\Reception;
use App\Entity\TypeReception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReceptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('horaireDebut', null, [
                'widget' => 'single_text',
            ])
            ->add('horaireFin', null, [
                'widget' => 'single_text',
            ])
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'id',
            ])
            ->add('typeReception', EntityType::class, [
                'class' => TypeReception::class,
                'choice_label' => 'id',
            ])
            ->add('jour', EntityType::class, [
                'class' => Jours::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reception::class,
        ]);
    }
}
