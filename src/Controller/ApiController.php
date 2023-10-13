<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private $servicesController;
    
    private $horairesOuvertureController;

    private $temoignagesController;

    private $voituresOccasionController;

    private $contactsController;

    public function __construct(
        ServicesController $servicesController,
        HorairesouvertureController $horairesOuvertureController,
        TemoignagesController $temoignagesController,
        VoituresoccasionController $voituresOccasionController,
        ContactsController $contactsController
    )
    {
        $this->servicesController = $servicesController;
        $this->horairesOuvertureController = $horairesOuvertureController;
        $this->temoignagesController= $temoignagesController;
        $this->voituresOccasionController = $voituresOccasionController;
        $this->contactsController = $contactsController;
    }
    #[Route('/api/services', name: 'app_services')]
    public function getServ(EntityManagerInterface $e)
    {
        return $this->servicesController->getServices($e);
    }

    #[Route('/api/voitures', name: 'app_voitures')]
    public function getVoitures(EntityManagerInterface $e)
    {
        return $this->voituresOccasionController->getVoitures($e);
    }

    #[Route('/api/temoignages', name: 'app_temoignages')]
    public function getTemoignages(EntityManagerInterface $e)
    {
        return $this->temoignagesController->getTemoignages($e);
    }
    #[Route('/api/horaires', name: 'app_horaires')]
    public function getHoraires(EntityManagerInterface $e)
    {
        return $this->horairesOuvertureController->getHoraires($e);
    }
}
