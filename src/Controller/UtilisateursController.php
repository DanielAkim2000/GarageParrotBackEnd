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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/utilisateurs')]
class UtilisateursController extends AbstractController
{
    #[Route('/', name: 'app_utilisateurs_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $utilisateurs = $entityManager
            ->getRepository(Utilisateurs::class)
            ->findAll();

        return $this->render('utilisateurs/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/new', name: 'app_utilisateurs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateurs();
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateurs/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateurs_show', methods: ['GET'])]
    public function show(Utilisateurs $utilisateur): Response
    {
        return $this->render('utilisateurs/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateurs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateurs $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateurs/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateurs_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateurs $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateurs_index', [], Response::HTTP_SEE_OTHER);
    }

    public function getEmploye(EntityManagerInterface $entityManager): Response
    {
        $utilisateursRepository = $entityManager->getRepository(Utilisateurs::class);
        //filtre permettant de faire une instruction where
        $queryBuilder = $utilisateursRepository->createQueryBuilder('employe');
        $queryBuilder
            ->where('employe.roles = :valeur')
            ->setParameter('valeur', 'N;');
        // Ajout des employes dans utilisateurs
        $utilisateurs = $queryBuilder->getQuery()->getResult();
            
        return $this->json(
            ['utilisateurs' => $utilisateurs ]
        ,200,[],['groups' => 'Employe']);
    }

    public function editEmploye(Request $request, Utilisateurs $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateurs/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

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

    /**
     * @Route("/", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = $request->request->all();

        if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $utilisateur = new Utilisateurs();
        $utilisateur->setFirstname($data['firstname']);
        $utilisateur->setLastname($data['lastname']);
        $utilisateur->setEmail($data['email']);
        $utilisateur->setPassword($data['password']); // Assurez-vous d'ajuster cette partie en fonction de votre logique.

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

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function showEmploye(Utilisateurs $utilisateur): Response
    {
        if (!$utilisateur) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $utilisateur->getId(),
            'firstname' => $utilisateur->getFirstname(),
            'lastname' => $utilisateur->getLastname(),
            'email' => $utilisateur->getEmail(),
            'roles' => $utilisateur->getRoles(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(Request $request, Utilisateurs $utilisateur): Response
    {
        if (!$utilisateur) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();

        if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
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

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
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
