<?php

namespace App\Controller;

use App\Entity\Temoignages;
use App\Form\TemoignagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/temoignages')]
class TemoignagesController extends AbstractController
{
    #[Route('/', name: 'app_temoignages_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $temoignages = $entityManager
            ->getRepository(Temoignages::class)
            ->findAll();

        return $this->render('temoignages/index.html.twig', [
            'temoignages' => $temoignages,
        ]);
    }

    #[Route('/new', name: 'app_temoignages_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $temoignage = new Temoignages();
        $form = $this->createForm(TemoignagesType::class, $temoignage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($temoignage);
            $entityManager->flush();

            return $this->redirectToRoute('app_temoignages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('temoignages/new.html.twig', [
            'temoignage' => $temoignage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_temoignages_show', methods: ['GET'])]
    public function show(Temoignages $temoignage): Response
    {
        return $this->render('temoignages/show.html.twig', [
            'temoignage' => $temoignage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_temoignages_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Temoignages $temoignage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TemoignagesType::class, $temoignage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_temoignages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('temoignages/edit.html.twig', [
            'temoignage' => $temoignage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_temoignages_delete', methods: ['POST'])]
    public function delete(Request $request, Temoignages $temoignage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$temoignage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($temoignage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_temoignages_index', [], Response::HTTP_SEE_OTHER);
    }

    public function getTemoignages(EntityManagerInterface $entityManager): Response
    {
        $temoignages = $entityManager
            ->getRepository(Temoignages::class)
            ->findAll();

        return $this->json([
            'temoignages' => $temoignages,
        ],200,[],['groups'=>'Temoignages']);
    }

    public function insert(Request $request,EntityManagerInterface $entityManager){
        try{
            $temoignagesEntity = new Temoignages();
            $temoignages = json_decode($request->getContent(),true);
            if(isset($temoignages['nom'])){
                $temoignagesEntity->setNom($temoignages['nom']);
            }
            else{
                throw new Exception('Précisez votre nom');
            }
            if(isset($temoignages['note'])){
                $temoignagesEntity->setNote($temoignages['note']);
            }
            else{
                throw new Exception('Laisser une note');
            }
            if(isset($temoignages['commentaire'])){
                $temoignagesEntity->setCommentaire($temoignages['commentaire']);
            }
            else{
                throw new Exception('Laisser une commentaire');
            }
            $entityManager->persist($temoignagesEntity);
            $entityManager->flush();
            return $this->json(['message' => 'Votre avis a bien été envoyé']);
        }
        catch(Exception $e){
            return $this->json(['message' => $e->getMessage()]);
        }
    }

    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function indexTemoignages(): Response
    {
        $temoignages = $this->entityManager->getRepository(Temoignages::class)->findAll();
        $data = [];

        foreach ($temoignages as $temoignage) {
            $data[] = [
                'id' => $temoignage->getId(),
                'nom' => $temoignage->getNom(),
                'commentaire' => $temoignage->getCommentaire(),
                'note' => $temoignage->getNote(),
                'modere' => $temoignage->isModere(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['commentaire']) || empty($data['nom']) || empty($data['note'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $temoignage = new Temoignages();
        $temoignage->setNom($data['nom'] ?? null);
        $temoignage->setCommentaire($data['commentaire']);
        $temoignage->setNote($data['note'] ?? null);
        $temoignage->setModere($data['modere'] ?? false);

        $errors = $this->validator->validate($temoignage);

        if (count($errors) === 0) {
            $this->entityManager->persist($temoignage);
            $this->entityManager->flush();

            $data = [
                'id' => $temoignage->getId(),
                'nom' => $temoignage->getNom(),
                'commentaire' => $temoignage->getCommentaire(),
                'note' => $temoignage->getNote(),
                'modere' => $temoignage->isModere(),
            ];

            return new JsonResponse($data, Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function showTemoignages(Temoignages $temoignage): Response
    {
        if (!$temoignage) {
            return new JsonResponse(['error' => 'Témoignage non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $temoignage->getId(),
            'nom' => $temoignage->getNom(),
            'commentaire' => $temoignage->getCommentaire(),
            'note' => $temoignage->getNote(),
            'modere' => $temoignage->isModere(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(Request $request, Temoignages $temoignage): Response
    {
        if (!$temoignage) {
            return new JsonResponse(['error' => 'Témoignage non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['commentaire'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $temoignage->setNom($data['nom'] ?? null);
        $temoignage->setCommentaire($data['commentaire']);
        $temoignage->setNote($data['note'] ?? null);
        $temoignage->setModere($data['modere'] ?? false);

        $errors = $this->validator->validate($temoignage);

        if (count($errors) === 0) {
            $this->entityManager->flush();

            $data = [
                'id' => $temoignage->getId(),
                'nom' => $temoignage->getNom(),
                'commentaire' => $temoignage->getCommentaire(),
                'note' => $temoignage->getNote(),
                'modere' => $temoignage->isModere(),
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteTemoignages(Temoignages $temoignage): Response
    {
        if (!$temoignage) {
            return new JsonResponse(['error' => 'Témoignage non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($temoignage);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
