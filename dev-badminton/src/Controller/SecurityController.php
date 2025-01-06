<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClubRepository;
use App\Service\ClubService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

    
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            
        ]);
    }

    #[Route(path: '/LoginRedirection', name: 'LoginRedirection')]
    public function redirectAfterLogin(LoggerInterface $logger , ClubRepository $clubRepository , ClubService $clubService): RedirectResponse
    {
        // Vérifier le rôle et rediriger vers le dashboard correspondant
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('superAdmin');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('superAdmin');
        }

        if ($this->isGranted('ROLE_CLUB')) {

            $user = $this->getUser();
            if ($user){
                $club = $clubService->getClubFromUser($user);
                if ($club) {
                    $clubId = $club->getId();
                    return $this->redirectToRoute('app_club_show', ['id' => $clubId]);
                }
                
            }
        }

        // Redirection par défaut si aucun rôle
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/change-password', name: 'app_change_password')]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est connecté
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'You must be logged in to change your password.');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $currentPassword = $request->request->get('current_password');
            $newPassword = $request->request->get('new_password');
            $confirmPassword = $request->request->get('confirm_password');

            // Vérifiez le mot de passe actuel
            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', 'Current password is incorrect.');
                return $this->redirectToRoute('app_change_password');
            }

            // Vérifiez si les mots de passe correspondent
            if ($newPassword !== $confirmPassword) {
                $this->addFlash('error', 'New password and confirm password do not match.');
                return $this->redirectToRoute('app_change_password');
            }

            // Encodez et mettez à jour le mot de passe
            $encodedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($encodedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Password changed successfully.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/passwordChange.html.twig');
    }

}
