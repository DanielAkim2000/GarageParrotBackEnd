<?php

namespace App\Controller;

use App\Entity\Voituresoccasion;
use App\Service\ImageManager;
use App\Form\VoituresoccasionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Filesystem\Filesystem;

#[Route('/voituresoccasion')]
class VoituresoccasionController extends AbstractController
{
    private $validator;
    private $entityManager; 
    private $imageManager;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        ImageManager $imageManager
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->imageManager = $imageManager;
    }
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexVoitures(): Response
    {
        $voituresoccasion = $this->entityManager->getRepository(Voituresoccasion::class)->findAll();
        $data = [];

        foreach ($voituresoccasion as $voiture) {
            $imageLink = $this->imageManager->generateImageLink($voiture->getImagePath()); // Génère le lien de l'image
            $data[] = [
                'id' => $voiture->getId(),
                'marque' => $voiture->getMarque(),
                'modele' => $voiture->getModele(),
                'annee_mise_en_circulation' => $voiture->getAnneeMiseEnCirculation(),
                'prix' => $voiture->getPrix(),
                'kilometrage' => $voiture->getKilometrage(),
                'image' => $imageLink,
                'description' => $voiture->getDescription(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = $request->request->all();

        if (empty($data['marque']) || empty($data['modele']) || empty($data['annee_mise_en_circulation']) || empty($data['prix']) || empty($data['kilometrage'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $voiture = new Voituresoccasion();
        $voiture->setMarque($data['marque']);
        $voiture->setModele($data['modele']);
        $voiture->setAnneeMiseEnCirculation($data['annee_mise_en_circulation']);
        $voiture->setPrix($data['prix']);
        $voiture->setKilometrage($data['kilometrage']);
        $voiture->setDescription($data['description']);

        if ($request->files->has('image')) {
            $imageFile = $request->files->get('image');
            $newImageName = $this->imageManager->upload($imageFile);
            $voiture->setImagePath($newImageName);
        }

        $errors = $this->validator->validate($voiture);

        if (count($errors) === 0) {
            $this->entityManager->persist($voiture);
            $this->entityManager->flush();
            $imageLink = $this->imageManager->generateImageLink($newImageName);
            $data = [
                'id' => $voiture->getId(),
                'marque' => $voiture->getMarque(),
                'modele' => $voiture->getModele(),
                'annee_mise_en_circulation' => $voiture->getAnneeMiseEnCirculation(),
                'prix' => $voiture->getPrix(),
                'kilometrage' => $voiture->getKilometrage(),
                'image' => $imageLink,
                'description' => $voiture->getDescription(),
            ];
            return new JsonResponse($data, Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function showVoitures(Voituresoccasion $voiture): Response
    {
        if (!$voiture) {
            return new JsonResponse(['error' => 'Voiture non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $imageLink = $this->imageManager->generateImageLink($voiture->getImagePath()); // Génère le lien de l'image
        $data = [
            'id' => $voiture->getId(),
            'marque' => $voiture->getMarque(),
            'modele' => $voiture->getModele(),
            'annee_mise_en_circulation' => $voiture->getAnneeMiseEnCirculation(),
            'prix' => $voiture->getPrix(),
            'kilometrage' => $voiture->getKilometrage(),
            'image' => $imageLink,
            'description' => $voiture->getDescription(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(Request $request,string $id): Response
    {
        $voiture = $this->entityManager->getRepository(Voituresoccasion::class)->find($id);

        if (!$voiture) {
            return new JsonResponse(['error' => 'Service non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();

        if (empty($data['marque']) || empty($data['modele']) || empty($data['annee_mise_en_circulation']) || empty($data['prix']) || empty($data['kilometrage'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $voiture->setMarque($data['marque']);
        $voiture->setModele($data['modele']);
        $voiture->setAnneeMiseEnCirculation($data['annee_mise_en_circulation']);
        $voiture->setPrix($data['prix']);
        $voiture->setKilometrage($data['kilometrage']);
        $voiture->setDescription($data['description']);

        if ($request->files->has('image')) {
            $imageFile = $request->files->get('image');
            if ($voiture->getImagePath()) {
                $this->imageManager->remove($voiture->getImagePath());
            }
            $newImageName = $this->imageManager->upload($imageFile);
            $voiture->setImagePath($newImageName);
        }

        $errors = $this->validator->validate($voiture);

        if (count($errors) === 0) {
            $this->entityManager->flush();
            $imageLink = $this->imageManager->generateImageLink($voiture->getImagePath()); // Génère le lien de l'image
            $data = [
                'id' => $voiture->getId(),
                'marque' => $voiture->getMarque(),
                'modele' => $voiture->getModele(),
                'annee_mise_en_circulation' => $voiture->getAnneeMiseEnCirculation(),
                'prix' => $voiture->getPrix(),
                'kilometrage' => $voiture->getKilometrage(),
                'image' => $imageLink,
                'description' => $voiture->getDescription(),
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteVoiture(Voituresoccasion $voiture): Response
    {
        if (!$voiture) {
            return new JsonResponse(['error' => 'Voiture non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($voiture);
        $this->entityManager->flush();

        // Supprimez l'image associée si nécessaire
        if (!empty($voiture->getImagePath())) {
            $this->imageManager->remove($voiture->getImagePath());
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

