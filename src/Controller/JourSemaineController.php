<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\JourSemaine;

class JourSemaineController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
 * @Route("/create-jour-semaine", name="create_weekdays", methods={"GET"})
 */
    public function createJoursemaine(): JsonResponse
    {
        // Supprimer tous les jours existants avant de les recréer (facultatif)
        $this->entityManager->getRepository(JourSemaine::class)->truncateTable();

        $weekdays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimache'];

        foreach ($weekdays as $dayName) {
            $jourSemaine = new JourSemaine();
            $jourSemaine->setId($dayName);

            $this->entityManager->persist($jourSemaine);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Weekdays created successfully']);
    }
    public function listeJoursSemaine()
    {
        $repository = $this->entityManager->getRepository(JourSemaine::class);
        $joursSemaine = $repository->findAll();

        $data = array_map(function (JourSemaine $jourSemaine) {
            return $jourSemaine->getId();
        }, $joursSemaine);

        return new JsonResponse($data);
    }
}

