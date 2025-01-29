<?php

namespace App\Controller;

use App\Entity\Reception;
use App\Form\Reception1Type;
use App\Service\ReceptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ReceptionType;

#[Route('/reception')]
final class ReceptionController extends AbstractController
{
    #[Route(name: 'app_reception_index', methods: ['GET'])]
    public function index(ReceptionService $receptionService, Request $request): Response
    {
        $pagination = $receptionService->getPaginatedReceptions($request);
        return $this->render('reception/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_reception_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reception = new Reception();
        $form = $this->createForm(ReceptionType::class, $reception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reception);
            $entityManager->flush();

            return $this->redirectToRoute('app_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reception/new.html.twig', [
            'reception' => $reception,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reception_show', methods: ['GET'])]
    public function show(Reception $reception): Response
    {
        return $this->render('reception/show.html.twig', [
            'reception' => $reception,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reception_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reception $reception, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReceptionType::class, $reception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reception/edit.html.twig', [
            'reception' => $reception,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reception_delete', methods: ['POST'])]
    public function delete(Request $request, Reception $reception, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reception->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reception);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reception_index', [], Response::HTTP_SEE_OTHER);
    }

}
