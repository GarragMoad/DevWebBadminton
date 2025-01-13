<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Enum\Classement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            'include_equipes' => true,
        ]);
    }
}
