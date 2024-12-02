<?php

namespace App\Form;
use App\Entity\Equipe;
use App\Service\EquipeService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class JoueurEquipeType extends AbstractType
{

    private $equipeService;
    private $security;
    public function __construct(EquipeService $equipeService , Security $security)
    {
        $this->equipeService = $equipeService;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $builder
            ->add('equipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'Nom_equipe',
                'choices' => $this->equipeService->getEquipesFromUser($user),
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