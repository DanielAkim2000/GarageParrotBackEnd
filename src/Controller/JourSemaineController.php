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

