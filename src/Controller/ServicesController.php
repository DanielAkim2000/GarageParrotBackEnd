<?php

namespace App\Controller;

use App\Entity\Services;
use App\Service\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface; 

class ServicesController extends AbstractController
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

    public function indexServices(): Response
    {
        $services = $this->entityManager->getRepository(Services::class)->findAll();
        $data = [];

        foreach ($services as $service) {
            $imageLink = $this->imageManager->generateImageLink($service->getImageName());

            $data[] = [
                'id' => $service->getId(),
                'nom' => $service->getNom(),
                'description' => $service->getDescription(),
                'image' => $imageLink,
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function create(Request $request): Response
    {
        $data = $request->request->all();

        if (empty($data['nom'])) {
            return new JsonResponse(['error' => 'Le champ "nom" est requis'], Response::HTTP_BAD_REQUEST);
        }

        $service = new Services();
        $service->setNom($data['nom']);
        $service->setDescription($data['description'] ?? null);

        if ($request->files->has('image')) {
            $imageFile = $request->files->get('image');
            $newImageName = $this->imageManager->upload($imageFile);
            $service->setImageName($newImageName);
        }
    
        $errors = $this->validator->validate($service);
    
        if (count($errors) === 0) {
            $this->entityManager->persist($service);
            $this->entityManager->flush();
            $imageLink = $this->imageManager->generateImageLink($newImageName);
    
            $data = [
                'id' => $service->getId(),
                'nom' => $service->getNom(),
                'description' => $service->getDescription(),
                'image' => $imageLink,
            ];

            return new JsonResponse($data, Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, int $id): Response
    {
        $service = $this->entityManager->getRepository(Services::class)->find($id);

        if (!$service) {
            return new JsonResponse(['error' => 'Service non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();

        if (empty($data['nom'])) {
            return new JsonResponse(['error' => 'Le champ "nom" est requis'], Response::HTTP_BAD_REQUEST);
        }

        $service->setNom($data['nom']);
        $service->setDescription($data['description'] ?? null);

        if ($request->files->has('image')) {
            $imageFile = $request->files->get('image');
            if ($service->getImageName()) {
                $this->imageManager->remove($service->getImageName());
            }
            $newImageName = $this->imageManager->upload($imageFile);
            $service->setImageName($newImageName);
        }

        $errors = $this->validator->validate($service);

        if (count($errors) === 0) {
            $this->entityManager->flush();

            $imageLink = $this->imageManager->generateImageLink($service->getImageName());

            $data = [
                'id' => $service->getId(),
                'nom' => $service->getNom(),
                'description' => $service->getDescription(),
                'image' => $imageLink,
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteService(int $id): Response
    {
        $service = $this->entityManager->getRepository(Services::class)->find($id);

        if (!$service) {
            return new JsonResponse(['error' => 'Service non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($service);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
