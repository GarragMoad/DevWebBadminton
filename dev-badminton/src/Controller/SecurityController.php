<?php

namespace App\Controller;

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
    public function redirectAfterLogin(LoggerInterface $logger): RedirectResponse
    {
        // Vérifier le rôle et rediriger vers le dashboard correspondant
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('admin');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }

        if ($this->isGranted('ROLE_CLUB')) {
            return $this->redirectToRoute('app_joueur_index');
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
