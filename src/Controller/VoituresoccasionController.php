<?php

namespace App\Controller;

use App\Entity\Voituresoccasion;
use App\Form\VoituresoccasionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/voituresoccasion')]
class VoituresoccasionController extends AbstractController
{
    #[Route('/', name: 'app_voituresoccasion_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $voituresoccasions = $entityManager
            ->getRepository(Voituresoccasion::class)
            ->findAll();

        return $this->render('voituresoccasion/index.html.twig', [
            'voituresoccasions' => $voituresoccasions,
        ]);
    }

    #[Route('/new', name: 'app_voituresoccasion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voituresoccasion = new Voituresoccasion();
        $form = $this->createForm(VoituresoccasionType::class, $voituresoccasion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voituresoccasion);
            $entityManager->flush();

            return $this->redirectToRoute('app_voituresoccasion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voituresoccasion/new.html.twig', [
            'voituresoccasion' => $voituresoccasion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voituresoccasion_show', methods: ['GET'])]
    public function show(Voituresoccasion $voituresoccasion): Response
    {
        return $this->render('voituresoccasion/show.html.twig', [
            'voituresoccasion' => $voituresoccasion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_voituresoccasion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voituresoccasion $voituresoccasion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoituresoccasionType::class, $voituresoccasion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voituresoccasion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voituresoccasion/edit.html.twig', [
            'voituresoccasion' => $voituresoccasion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voituresoccasion_delete', methods: ['POST'])]
    public function delete(Request $request, Voituresoccasion $voituresoccasion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voituresoccasion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($voituresoccasion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voituresoccasion_index', [], Response::HTTP_SEE_OTHER);
    }
}
