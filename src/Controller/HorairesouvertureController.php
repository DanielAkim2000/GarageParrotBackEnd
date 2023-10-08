<?php

namespace App\Controller;

use App\Entity\Horairesouverture;
use App\Form\HorairesouvertureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/horairesouverture')]
class HorairesouvertureController extends AbstractController
{
    #[Route('/', name: 'app_horairesouverture_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $horairesouvertures = $entityManager
            ->getRepository(Horairesouverture::class)
            ->findAll();

        return $this->render('horairesouverture/index.html.twig', [
            'horairesouvertures' => $horairesouvertures,
        ]);
    }

    #[Route('/new', name: 'app_horairesouverture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $horairesouverture = new Horairesouverture();
        $form = $this->createForm(HorairesouvertureType::class, $horairesouverture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($horairesouverture);
            $entityManager->flush();

            return $this->redirectToRoute('app_horairesouverture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('horairesouverture/new.html.twig', [
            'horairesouverture' => $horairesouverture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_horairesouverture_show', methods: ['GET'])]
    public function show(Horairesouverture $horairesouverture): Response
    {
        return $this->render('horairesouverture/show.html.twig', [
            'horairesouverture' => $horairesouverture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_horairesouverture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Horairesouverture $horairesouverture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HorairesouvertureType::class, $horairesouverture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_horairesouverture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('horairesouverture/edit.html.twig', [
            'horairesouverture' => $horairesouverture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_horairesouverture_delete', methods: ['POST'])]
    public function delete(Request $request, Horairesouverture $horairesouverture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$horairesouverture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($horairesouverture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_horairesouverture_index', [], Response::HTTP_SEE_OTHER);
    }
}
