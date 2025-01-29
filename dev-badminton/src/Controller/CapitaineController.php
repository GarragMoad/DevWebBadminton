<?php

namespace App\Controller;

use App\Entity\Capitaine;
use App\Form\CapitaineType;
use App\Repository\CapitaineRepository;
use App\Service\CapitaineService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capitaine')]
final class CapitaineController extends AbstractController
{
    #[Route(name: 'app_capitaine_index', methods: ['GET'])]
    public function index(Request $request, CapitaineService $capitaineService): Response
    {

        $pagination = $capitaineService->getPaginatedCapitaines($request);
        return $this->render('capitaine/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_capitaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $capitaine = new Capitaine();
        $form = $this->createForm(CapitaineType::class, $capitaine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $equipe = $capitaine->getEquipes();
            foreach ($equipe as $equipe){
                $equipe->setCapitaine($capitaine);
            }
            $entityManager->persist($capitaine);
            $entityManager->flush();

            return $this->redirectToRoute('app_capitaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capitaine/new.html.twig', [
            'capitaine' => $capitaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_capitaine_show', methods: ['GET'])]
    public function show(Capitaine $capitaine): Response
    {
        return $this->render('capitaine/show.html.twig', [
            'capitaine' => $capitaine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_capitaine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Capitaine $capitaine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CapitaineType::class, $capitaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_capitaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capitaine/edit.html.twig', [
            'capitaine' => $capitaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_capitaine_delete', methods: ['POST'])]
    public function delete(Request $request, Capitaine $capitaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$capitaine->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($capitaine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_capitaine_index', [], Response::HTTP_SEE_OTHER);
    }
}
