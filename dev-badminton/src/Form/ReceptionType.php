<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Jours;
use App\Entity\Reception;
use App\Enum\TypeReception;
use App\Service\ClubService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ReceptionType extends AbstractType
{

    private Security $security;
    private ClubService $clubService;

    public function __construct(Security $security, ClubService $clubService)
    {
        $this->security = $security;
        $this->clubService = $clubService;
    }

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
                'choices' => $this->clubService->getClubForForm($this->security->getUser()),
                'choice_label' => 'nom',
            ])
            ->add('Type_reception', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(fn($c) => $c->value, TypeReception::cases()),
                    TypeReception::cases()
                ),
                'choice_label' => fn($choice) => $choice->value,
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