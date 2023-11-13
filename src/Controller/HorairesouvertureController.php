<?php

namespace App\Controller;

use App\Entity\Horairesouverture;
use App\Form\HorairesouvertureType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\JourSemaine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
    public function getHoraires(EntityManagerInterface $entityManager): Response
    {
        $horairesouvertures = $entityManager
            ->getRepository(Horairesouverture::class)
            ->findAll();

        $formattedHoraires = array_map(function ($horairesouverture) {
            return [
                'jourSemaine' => $horairesouverture->getJourSemaineFormatted(),
                'heureOuverture' => $horairesouverture->getHeureOuvertureFormatted(),
                'heureFermeture' => $horairesouverture->getHeureFermetureFormatted(),
                // Autres propriétés
            ];
        }, $horairesouvertures);

        return $this->json([
            'horairesouvertures' => $formattedHoraires,
        ], 200, [], ['groups' => 'horaires']);
    }

    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function indexHoraire(): Response
    {
        $horairesouverture = $this->entityManager->getRepository(Horairesouverture::class)->findAll();
        $data = [];
    
        foreach ($horairesouverture as $horaire) {
            $data[] = [
                'id' => $horaire->getId(),
                'jour_semaine' => $horaire->getJourSemaineFormatted(),
                'heure_ouverture' => $horaire->getHeureOuvertureFormatted(),
                'heure_fermeture' => $horaire->getHeureFermetureFormatted(),
            ];
        }
    
        // Triez le tableau $data par ordre alphabétique sur le champ 'jour_semaine'
        usort($data, function ($a, $b) {
            return strcmp($a['jour_semaine'], $b['jour_semaine']);
        });
    
        return new JsonResponse($data, Response::HTTP_OK);
    }
    
    /**
     * @Route("/", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = $request->request->all();

        if (empty($data['jour_semaine']) || empty($data['heure_ouverture']) || empty($data['heure_fermeture'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $jourSemaineId = $data['jour_semaine'];
        $jourSemaine = $this->entityManager->getRepository(JourSemaine::class)->find($jourSemaineId);

        if (!$jourSemaine) {
            return new JsonResponse(['error' => 'Jour de la semaine introuvable'], Response::HTTP_BAD_REQUEST);
        }
        $horaire = new Horairesouverture();
        // Assurez-vous d'ajuster les propriétés de $horaire en fonction des données JSON.
        $horaire->setJourSemaine($jourSemaine);
        $horaire->setHeureOuverture(new \DateTime($data['heure_ouverture']));
        $horaire->setHeureFermeture(new \DateTime($data['heure_fermeture']));

        $errors = $this->validator->validate($horaire);

        if (count($errors) === 0) {
            $this->entityManager->persist($horaire);
            $this->entityManager->flush();
            $data = [
                'id' => $horaire->getId(),
                'jour_semaine' => $horaire->getJourSemaineFormatted(),
                'heure_ouverture' => $horaire->getHeureOuvertureFormatted(),
                'heure_fermeture' => $horaire->getHeureFermetureFormatted(),
            ];
            return new JsonResponse($data, Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function showHoraire(Horairesouverture $horaire): Response
    {
        if (!$horaire) {
            return new JsonResponse(['error' => 'Horaire non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $horaire->getId(),
            'jour_semaine' => $horaire->getJourSemaineFormatted(),
            'heure_ouverture' => $horaire->getHeureOuvertureFormatted(),
            'heure_fermeture' => $horaire->getHeureFermetureFormatted(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(Request $request, Horairesouverture $horaire): Response
    {
        if (!$horaire) {
            return new JsonResponse(['error' => 'Horaire non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();

        if (empty($data['jour_semaine']) || empty($data['heure_ouverture']) || empty($data['heure_fermeture'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $jourSemaineId = $data['jour_semaine'];
        $jourSemaine = $this->entityManager->getRepository(JourSemaine::class)->find($jourSemaineId);

        if (!$jourSemaine) {
            return new JsonResponse(['error' => 'Jour de la semaine introuvable'], Response::HTTP_BAD_REQUEST);
        }

        // Assurez-vous d'ajuster les propriétés de $horaire en fonction des données JSON.
        $horaire->setJourSemaine($jourSemaine);
        $horaire->setHeureOuverture(new \DateTime($data['heure_ouverture']));
        $horaire->setHeureFermeture(new \DateTime($data['heure_fermeture']));

        $errors = $this->validator->validate($horaire);

        if (count($errors) === 0) {
            $this->entityManager->flush();
            $data = [
                'id' => $horaire->getId(),
                'jour_semaine' => $horaire->getJourSemaineFormatted(),
                'heure_ouverture' => $horaire->getHeureOuvertureFormatted(),
                'heure_fermeture' => $horaire->getHeureFermetureFormatted(),
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteHoraire(int $id): Response
    {
        $horaire = $this->entityManager->getRepository(Horairesouverture::class)->find($id);

        if (!$horaire) {
            return new JsonResponse(['error' => 'Service non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($horaire);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
