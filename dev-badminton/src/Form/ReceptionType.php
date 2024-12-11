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
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ReceptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('horaireDebut', TimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'input' => 'datetime',
                'attr' => ['step' => 60], // Optional: to set the step to 1 minute
            ])
            ->add('horaireFin', TimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'input' => 'datetime',
                'attr' => ['step' => 60], // Optional: to set the step to 1 minute
            ])
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'nom',
            ])
            ->add('typeReception', EntityType::class, [
                'class' => TypeReception::class,
                'choice_label' => 'id',
            ])
            ->add('jour', EntityType::class, [
                'class' => Jours::class,
                'choice_label' => 'jour',
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
