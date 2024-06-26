<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UtilisateursController extends AbstractController
{
    // public function getEmploye(EntityManagerInterface $entityManager): Response
    // {
    //     $utilisateursRepository = $entityManager->getRepository(Utilisateurs::class);
    //     $queryBuilder = $utilisateursRepository->createQueryBuilder('employe');
    //     $queryBuilder
    //         ->where('employe.roles = :valeur')
    //         ->setParameter('valeur', 'N;');
    //     $utilisateurs = $queryBuilder->getQuery()->getResult();
            
    //     return $this->json(
    //         ['utilisateurs' => $utilisateurs ]
    //     ,200,[],['groups' => 'Employe']);
    // }
    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function indexEmploye(): Response
    {
        $utilisateurs = $this->entityManager->getRepository(Utilisateurs::class)->createQueryBuilder('u')
            ->where('u.roles NOT LIKE :roleAdmin')
            ->setParameter('roleAdmin', '%ROLE_ADMIN%')
            ->getQuery()
            ->getResult();
            
        $data = [];

        foreach ($utilisateurs as $utilisateur) {
            $data[] = [
                'id' => $utilisateur->getId(),
                'firstname' => $utilisateur->getFirstname(),
                'lastname' => $utilisateur->getLastname(),
                'email' => $utilisateur->getEmail(),
                'roles' => $utilisateur->getRoles(),
                'password' => $utilisateur->getPassword(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    public function create(Request $request): Response
    {
        $data = $request->request->all();

        if (empty($data['firstname'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le nom'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['lastname']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le prénom'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['email']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'email'], Response::HTTP_BAD_REQUEST);
        }
        $utilisateur = new Utilisateurs();
        $utilisateur->setFirstname($data['firstname']);
        $utilisateur->setLastname($data['lastname']);
        $utilisateur->setEmail($data['email']);
        $utilisateur->setPassword($data['password']);
        $utilisateur->setRoles(["ROLE_USER"]);

        $errors = $this->validator->validate($utilisateur);

        if (count($errors) === 0) {
            $this->entityManager->persist($utilisateur);
            $this->entityManager->flush();
            $data = [
                'id' => $utilisateur->getId(),
                'firstname' => $utilisateur->getFirstname(),
                'lastname' => $utilisateur->getLastname(),
                'email' => $utilisateur->getEmail(),
                'roles' => $utilisateur->getRoles(),
            ];
            return new JsonResponse($data, Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, Utilisateurs $utilisateur): Response
    {
        if (!$utilisateur) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();

        if (empty($data['firstname'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le nom'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['lastname']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le prénom'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['email']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'email'], Response::HTTP_BAD_REQUEST);
        }

        $utilisateur->setFirstname($data['firstname']);
        $utilisateur->setLastname($data['lastname']);
        $utilisateur->setEmail($data['email']);

        $errors = $this->validator->validate($utilisateur);

        if (count($errors) === 0) {
            $this->entityManager->flush();
            $data = [
                'id' => $utilisateur->getId(),
                'firstname' => $utilisateur->getFirstname(),
                'lastname' => $utilisateur->getLastname(),
                'email' => $utilisateur->getEmail(),
                'roles' => $utilisateur->getRoles(),
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteEmploye(Utilisateurs $utilisateur): Response
    {
        if (!$utilisateur) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($utilisateur);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
