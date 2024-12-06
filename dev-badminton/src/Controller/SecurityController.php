<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    public function redirectAfterLogin(LoggerInterface $logger , ClubRepository $clubRepository): RedirectResponse
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
            if ($user && method_exists($user, 'getEmail')){
                $email = $user->getEmail();

                // Extraire la partie avant le @
                $clubName = explode('@', $email)[0];
                $club = $clubRepository->findOneBy(['nom' => $clubName]);
                if ($club) {
                    $clubId = $club->getId();
                    return $this->redirectToRoute('app_club_show', ['id' => $clubId]);
                }
                
            }
            return $this->redirectToRoute('app_club_index');
        }

        // Redirection par défaut si aucun rôle
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
