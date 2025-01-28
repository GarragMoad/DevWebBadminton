<?php
// src/Service/UserService

namespace App\Service;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\EquipeRepository;



class UserService
{
    private $entityManager;
    private $mailerService;

    private $router;

    private $formFactory;

    private $passwordHasher;

    private $security;

    private $equipeService;

    private $equipeRepository;


    public function __construct(EntityManagerInterface $entityManager, MailerService $mailerService , RouterInterface $router, FormFactoryInterface $formFactory, UserPasswordHasherInterface $userPasswordHasher, Security $security , EquipeService $equipeService , EquipeRepository $equipeRepository)
    {
        $this->entityManager = $entityManager;
        $this->mailerService = $mailerService;
        $this->router=$router;
        $this->formFactory=$formFactory;
        $this->passwordHasher=$userPasswordHasher;
        $this->security=$security;
        $this->equipeService=$equipeService;
        $this->equipeRepository=$equipeRepository;
    }

    /**
     * @throws \Exception
     */
    public function generateRandomPassword(){
        return bin2hex(random_bytes(8));
    }


    /**
     * @throws \Exception
     */
    public function createUser(Request $request ):array{

        $user = new User();
        $tempPassword = $this->generateRandomPassword();
        $form =  $this->formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEmail=$user->getEmail();
            // Hasher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $tempPassword
            );
            $user->setPassword($hashedPassword);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->mailerService->sendEmail(
                $userEmail,
                'DevWebBadminton Inscription' ,
                'Nous vous invitons à changer votre  mot de passe temporaire : ' . $tempPassword . ' en consultant la page .' );
            return ['redirect' => $this->router->generate('app_user_index')];
        }


            return  [
                'user' => $user,
                'form' => $form,
            ];
        }

        public function getUserEmail($user): string
        {
           return $user->getEmail();
        }

    public function getEquipesForUser($user)
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->equipeRepository->findAll(); // Toutes les équipes
        } elseif ($this->security->isGranted('ROLE_CLUB')) {
            return $this->equipeService->getEquipesFromUser($user); // Équipes filtrées
        }

        return []; // Pas d'équipes accessibles
    }



}