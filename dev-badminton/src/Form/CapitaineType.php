<?php

namespace App\Form;

use App\Entity\Capitaine;
use App\Entity\Equipe;
use App\Service\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class CapitaineType extends AbstractType
{

    private $userService;
    private $security;

    public function __construct(UserService $userService, Security $security)
    {
        $this->userService = $userService;
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('mail')
            ->add('telephone')
            ->add('equipes', EntityType::class, [
                'class' => Equipe::class,
                'choices' => $this->userService->getEquipesForUser($user),
                'choice_label' => function(Equipe $equipe) {
                    return $equipe->getNomEquipe();
                },
                'multiple' => true,
                'expanded' => true,
                'mapped' => true,

            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Capitaine::class,
        ]);
    }
}
