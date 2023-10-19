<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    private $servicesController;
    
    private $horairesOuvertureController;

    private $temoignagesController;

    private $voituresOccasionController;

    private $contactsController;

    private $utilisateursController;

    public function __construct(
        ServicesController $servicesController,
        HorairesouvertureController $horairesOuvertureController,
        TemoignagesController $temoignagesController,
        VoituresoccasionController $voituresOccasionController,
        ContactsController $contactsController,
        UtilisateursController $utilisateursController
    )
    {
        $this->servicesController = $servicesController;
        $this->horairesOuvertureController = $horairesOuvertureController;
        $this->temoignagesController= $temoignagesController;
        $this->voituresOccasionController = $voituresOccasionController;
        $this->contactsController = $contactsController;
        $this->utilisateursController = $utilisateursController;
    }

    public function getServices(EntityManagerInterface $e)
    {
        return $this->servicesController->getServices($e);
    }

    public function getVoitures(EntityManagerInterface $e)
    {
        return $this->voituresOccasionController->getVoitures($e);
    }

    public function getTemoignages(EntityManagerInterface $e)
    {
        return $this->temoignagesController->getTemoignages($e);
    }
    
    public function getHoraires(EntityManagerInterface $e)
    {
        return $this->horairesOuvertureController->getHoraires($e);
    }

    public function insertContact(Request $request,EntityManagerInterface $e)
    {
        
        return $this->contactsController->insert($request,$e);
    }

    public function insertAvis(Request $request,EntityManagerInterface $e)
    {
        
        return $this->temoignagesController->insert($request,$e);
    }

    public function getEmploye(EntityManagerInterface $e)
    {
        
        return $this->utilisateursController->getEmploye($e);
    }


}
