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
    public function indexVoitures(): Response
    {
        $voituresoccasion = $this->entityManager->getRepository(Voituresoccasion::class)->findAll();
        $data = [];

        foreach ($voituresoccasion as $voiture) {
            $imageLink = $this->imageManager->generateImageLink($voiture->getImagePath());
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
    public function create(Request $request): Response
    {
        $data = $request->request->all();

        if (empty($data['marque'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le marque'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['modele']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le modèle'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['annee_mise_en_circulation']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'année de mise en circulation'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['prix']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le prix'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['kilometrage']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le kilométrage'], Response::HTTP_BAD_REQUEST);
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
    public function showVoitures(Voituresoccasion $voiture): Response
    {
        if (!$voiture) {
            return new JsonResponse(['error' => 'Voiture non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $imageLink = $this->imageManager->generateImageLink($voiture->getImagePath());
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

    public function update(Request $request,string $id): Response
    {
        $voiture = $this->entityManager->getRepository(Voituresoccasion::class)->find($id);

        if (!$voiture) {
            return new JsonResponse(['error' => 'Service non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();

        if (empty($data['marque'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le marque'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['modele']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le modèle'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['annee_mise_en_circulation']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'année de mise en circulation'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['prix']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le prix'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['kilometrage']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le kilométrage'], Response::HTTP_BAD_REQUEST);
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
            $imageLink = $this->imageManager->generateImageLink($voiture->getImagePath());
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

    public function deleteVoiture(Voituresoccasion $voiture): Response
    {
        if (!$voiture) {
            return new JsonResponse(['error' => 'Voiture non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($voiture);
        $this->entityManager->flush();

        if (!empty($voiture->getImagePath())) {
            $this->imageManager->remove($voiture->getImagePath());
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function filtreVoiture(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $kilometrage = $data['kilometrage'] ?? null;
        $prix = $data['prix'] ?? null;
        $annee = $data['annee'] ?? null;

        $queryBuilder = $this->entityManager->getRepository(Voituresoccasion::class)->createQueryBuilder('e');
        
        if ($kilometrage !== null) {
            $queryBuilder->andWhere('e.kilometrage > :kilometrage')->setParameter('kilometrage', $kilometrage);
        }

        if ($prix !== null) {
            $queryBuilder->andWhere('e.prix > :prix')->setParameter('prix', $prix);
        }

        if ($annee !== null) {
            $queryBuilder->andWhere('e.anneeMiseEnCirculation > :annee')->setParameter('annee', $annee);
        }

        $voituresoccasion= $queryBuilder->getQuery()->getResult();

        $data = [];

        foreach ($voituresoccasion as $voiture) {
            $imageLink = $this->imageManager->generateImageLink($voiture->getImagePath());
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
        return new JsonResponse($data,Response::HTTP_OK);
    }
}

