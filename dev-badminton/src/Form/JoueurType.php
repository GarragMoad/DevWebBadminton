<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Joueur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Service\EquipeService;
use App\Repository\EquipeRepository;

class JoueurType extends AbstractType
{
    private $security;

    private $equipeService;

    private $equipeRepository;

    public function __construct(Security $security , EquipeService $equipeService , EquipeRepository $equipeRepository)
    {
        $this->security = $security;
        $this->equipeService = $equipeService;
        $this->equipeRepository = $equipeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('nom')
            ->add('prenom')
            ->add('numreo_licence')
            ->add('classement_simple')
            ->add('cpph_simple')
            ->add('classement_double')
            ->add('cpph_double')
            ->add('classement_mixtes')
            ->add('cpph_mixtes')

        ;
        if($options['include_equipes']){
            $builder->add('equipes', EntityType::class, [
                'class' => Equipe::class,
                'choices' => $this->getEquipesForUser($user),
                'choice_label' => function(Equipe $equipe) {
                    return $equipe->getNomEquipe();
                },
                'multiple' => true,
                'expanded' => true,
                'mapped' => true,
                
            ]);
        }
               
            
    }

    private function getEquipesForUser($user)
    {
        if ($this->security->isGranted('ROLE_ADMIN' || 'ROLE_SUPER_ADMIN')) {
            return $this->equipeRepository->findAll();
        } elseif ($this->security->isGranted('ROLE_CLUB')) {
            $equipes = $this->equipeService->getEquipesFromUser($user);
            return $equipes ? [$equipes] : [];
        }
        return [];
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
            'include_equipes' => false,
        ]);
    }
}
