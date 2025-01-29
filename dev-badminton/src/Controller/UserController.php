<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\UserService;


#[Route('/user')]
final class UserController extends AbstractController
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route( name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $paginatedUsers = $this->userService->getPaginatedUsers($request);
        return $this->render('user/index.html.twig', [
            'pagination' => $paginatedUsers,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $formViews= $this->userService->createUser($request);

        if (isset($formViews['redirect'])) {
            return $this->redirect($formViews['redirect']);
        }
        return $this->render('user/new.html.twig', [
            'user' => $formViews['user'], 'form' => $formViews['form']

        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
/*
    #[Route('/password-reset', name: 'app_password_reset_request')]
    public function passwordResetRequest(Request $request, UserRepository $userRepository, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                // Générer un mot de passe temporaire
                $temporaryPassword = bin2hex(random_bytes(4)); // 8 caractères hexadécimaux
                $user->setPassword(password_hash($temporaryPassword, PASSWORD_BCRYPT));

                // Sauvegarder le mot de passe dans la base de données
                $entityManager->flush();

                // Envoyer l'email
                $emailMessage = (new Email())
                    ->from('noreply@example.com')
                    ->to($email)
                    ->subject('Réinitialisation de votre mot de passe')
                    ->html("<p>Votre mot de passe temporaire est : <strong>{$temporaryPassword}</strong></p>");

                $mailer->send($emailMessage);

                $this->addFlash('success', 'Un mot de passe temporaire vous a été envoyé.');
            } else {
                $this->addFlash('error', 'Cet email n’existe pas dans notre base de données.');
            }
        }

        return $this->render('security/password_reset_request.html.twig');
    }*/
}
