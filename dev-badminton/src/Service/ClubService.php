<?php

namespace App\Service;

use App\Entity\Club;
use App\Entity\Reception;
use App\Entity\Equipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClubType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\RouterInterface; // Import du RouterInterface
use App\Entity\User;

class ClubService
{
    private $entityManager;
    private $formFactory;
    private $router;

    private $mailerService;
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, RouterInterface $router, MailerService $mailerService)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->mailerService=$mailerService;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function createClub(Request $request): array
    {   
        // Création du club 
        $club = new Club();

        // Créer le formulaire pour le club
        $ClubForm = $this->formFactory->create(ClubType::class, $club);
        $ClubForm->handleRequest($request);
        if ($ClubForm->isSubmitted() ) {
            $email = $ClubForm->get('email')->getData();
            // Créer un user pour le club
            $this->createClubIdentification($club , $email);
            // Persistons le club
            $this->entityManager->persist($club);
            $this->entityManager->flush();

                    return ['redirect' => $this->router->generate('app_club_index')];
            }

       
        return [
            'clubForm' => $ClubForm,
        ];
        
    }

    /**
     * @throws \Exception
     * @throws TransportExceptionInterface
     */
    public function createClubIdentification(Club $club , String $email): void
    {
        $user= new User();
        $password = bin2hex(random_bytes(8));
        $user->setPassword(password_hash($password ,PASSWORD_BCRYPT));
        $user->setEmail($email);
        $user->setRoles(['ROLE_CLUB']);
        $club->setUser($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->mailerService->sendEmail(
            $email,
            'DevWebBadminton Inscription' ,
            'Nous vous invitons à changer votre  mot de passe temporaire : ' . $password . 'en consultant la page Changer mot de passe .' );

    }

    public function editClub(Club $club, Request $request): array
    {
        $email = $club->getUser()->getEmail();
        $clubForm = $this->formFactory->create(ClubType::class, $club ,['email' => $email]);

        $clubForm->handleRequest($request);
        if ($clubForm->isSubmitted() && $clubForm->isValid()) {
            $email = $clubForm->get('email')->getData();

            if ($email) {
                $user = $club->getUser();
                if ($user) {
                    $user->setEmail($email);
                    $this->entityManager->flush();
                }
            }
            $this->entityManager->flush();
            return ['redirect' => $this->router->generate('app_club_index')];
        }
        return [
            'clubForm' => $clubForm, 'club' => $club
        ];
    }


    public function getClub(int $id): ?Club
    {
        return $this->entityManager->getRepository(Club::class)->find($id);
    }

    public function getAllClubs(): array
    {
        return $this->entityManager->getRepository(Club::class)->findAll();
    }

    public function updateClub(Club $club): Club
    {
        $this->entityManager->flush();
        return $club;
    }

    public function deleteClub(Club $club): void
    {
        $this->entityManager->remove($club);
        $this->entityManager->flush();
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => strtolower($club->getNom()) . '@example.com']);
        if ($user) {
            $this->deleteUser($user);
        }
    }

    public function addReceptionToClub(Club $club, Reception $reception): Club
    {
        $club->addReception($reception);
        $this->entityManager->flush();
        return $club;
    }

    public function removeReceptionFromClub(Club $club, Reception $reception): Club
    {
        $club->removeReception($reception);
        $this->entityManager->flush();
        return $club;
    }

    public function addEquipeToClub(Club $club, Equipe $equipe): Club
    {
        $club->addEquipe($equipe);
        $this->entityManager->flush();
        return $club;
    }

    public function removeEquipeFromClub(Club $club, Equipe $equipe): Club
    {
        $club->removeEquipe($equipe);
        $this->entityManager->flush();
        return $club;
    }

    public function deleteUser(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function getClubFromUser(User $user): ?club
    {
        $club = $this->entityManager->getRepository(User::class)->findClubByUser($user);
        return $club ? $club : null;
    }

}