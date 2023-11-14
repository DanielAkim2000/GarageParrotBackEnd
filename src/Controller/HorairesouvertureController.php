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
        usort($data, function ($a, $b) {
            return strcmp($a['jour_semaine'], $b['jour_semaine']);
        });
    
        return new JsonResponse($data, Response::HTTP_OK);
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
            ];
        }, $horairesouvertures);

        return $this->json([
            'horairesouvertures' => $formattedHoraires,
        ], 200, [], ['groups' => 'horaires']);
    }
    public function create(Request $request): Response
    {
        $data = $request->request->all();

        if (empty($data['jour_semaine'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le jour de la semaine'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['heure_ouverture']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'heure d\'ouverture'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['heure_fermeture']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'heure de fermeture'], Response::HTTP_BAD_REQUEST);
        }

        $jourSemaineId = $data['jour_semaine'];
        $jourSemaine = $this->entityManager->getRepository(JourSemaine::class)->find($jourSemaineId);

        if (!$jourSemaine) {
            return new JsonResponse(['error' => 'Jour de la semaine introuvable'], Response::HTTP_BAD_REQUEST);
        }
        $horaire = new Horairesouverture();
        
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

    public function update(Request $request, Horairesouverture $horaire): Response
    {
        if (!$horaire) {
            return new JsonResponse(['error' => 'Horaire non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();

        if (empty($data['jour_semaine'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le jour de la semaine'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['heure_ouverture']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'heure d\'ouverture'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['heure_fermeture']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'heure de fermeture'], Response::HTTP_BAD_REQUEST);
        }

        $jourSemaineId = $data['jour_semaine'];
        $jourSemaine = $this->entityManager->getRepository(JourSemaine::class)->find($jourSemaineId);

        if (!$jourSemaine) {
            return new JsonResponse(['error' => 'Jour de la semaine introuvable'], Response::HTTP_BAD_REQUEST);
        }

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
