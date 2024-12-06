<?php

namespace App\Controller;

use App\Entity\TypeReception;
use App\Form\TypeReceptionType;
use App\Repository\TypeReceptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type/reception')]
final class TypeReceptionController extends AbstractController
{
    #[Route(name: 'app_type_reception_index', methods: ['GET'])]
    public function index(TypeReceptionRepository $typeReceptionRepository): Response
    {
        return $this->render('type_reception/index.html.twig', [
            'type_receptions' => $typeReceptionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_reception_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeReception = new TypeReception();
        $form = $this->createForm(TypeReceptionType::class, $typeReception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeReception);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_reception/new.html.twig', [
            'type_reception' => $typeReception,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_reception_show', methods: ['GET'])]
    public function show(TypeReception $typeReception): Response
    {
        return $this->render('type_reception/show.html.twig', [
            'type_reception' => $typeReception,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_reception_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeReception $typeReception, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeReceptionType::class, $typeReception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_reception/edit.html.twig', [
            'type_reception' => $typeReception,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_reception_delete', methods: ['POST'])]
    public function delete(Request $request, TypeReception $typeReception, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeReception->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeReception);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_reception_index', [], Response::HTTP_SEE_OTHER);
    }
}
