<?php

namespace App\Controller;

use App\Entity\Equipementsoptions;
use App\Form\EquipementsoptionsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipementsoptions')]
class EquipementsoptionsController extends AbstractController
{
    #[Route('/', name: 'app_equipementsoptions_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $equipementsoptions = $entityManager
            ->getRepository(Equipementsoptions::class)
            ->findAll();

        return $this->render('equipementsoptions/index.html.twig', [
            'equipementsoptions' => $equipementsoptions,
        ]);
    }

    #[Route('/new', name: 'app_equipementsoptions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipementsoption = new Equipementsoptions();
        $form = $this->createForm(EquipementsoptionsType::class, $equipementsoption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($equipementsoption);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipementsoptions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipementsoptions/new.html.twig', [
            'equipementsoption' => $equipementsoption,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipementsoptions_show', methods: ['GET'])]
    public function show(Equipementsoptions $equipementsoption): Response
    {
        return $this->render('equipementsoptions/show.html.twig', [
            'equipementsoption' => $equipementsoption,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipementsoptions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipementsoptions $equipementsoption, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipementsoptionsType::class, $equipementsoption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipementsoptions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipementsoptions/edit.html.twig', [
            'equipementsoption' => $equipementsoption,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipementsoptions_delete', methods: ['POST'])]
    public function delete(Request $request, Equipementsoptions $equipementsoption, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipementsoption->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipementsoption);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipementsoptions_index', [], Response::HTTP_SEE_OTHER);
    }
}
