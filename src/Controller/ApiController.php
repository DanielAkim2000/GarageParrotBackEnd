<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Entity\Horairesouverture;
use App\Entity\Services;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\JourSemaine;
use App\Entity\Voituresoccasion;

class ApiController extends AbstractController
{
    private $servicesController;
    
    private $horairesOuvertureController;

    private $temoignagesController;

    private $voituresOccasionController;

    private $contactsController;

    private $utilisateursController;

    private $jourSemaineController;

    public function __construct(
        ServicesController $servicesController,
        HorairesouvertureController $horairesOuvertureController,
        TemoignagesController $temoignagesController,
        VoituresoccasionController $voituresOccasionController,
        ContactsController $contactsController,
        UtilisateursController $utilisateursController,
        JourSemaineController $jourSemaineController
    )
    {
        $this->servicesController = $servicesController;
        $this->horairesOuvertureController = $horairesOuvertureController;
        $this->temoignagesController= $temoignagesController;
        $this->voituresOccasionController = $voituresOccasionController;
        $this->contactsController = $contactsController;
        $this->utilisateursController = $utilisateursController;
        $this->jourSemaineController = $jourSemaineController;
    
    }
    

    //Users
    public function getEmploye()
    { 
        return $this->utilisateursController->indexEmploye();
    }

    public function editUsers(Request $request, Utilisateurs $utilisateur)
    {
        return $this->utilisateursController->update($request,$utilisateur);
    }

    public function deleteEmploye(Utilisateurs $utilisateur)
    {
        return $this->utilisateursController->deleteEmploye($utilisateur);
    }

    public function createUtilisateur(Request $request)
    {
        return $this->utilisateursController->create($request);
    }

    //Services
    public function getServices()
    {
        return $this->servicesController->indexServices();
    }

    public function createService(Request $request)
    {
        return $this->servicesController->create($request);
    }

    public function editService(Request $request,int $id)
    {
        return $this->servicesController->update($request,$id);
    }

    public function deleteService(int $id)
    {
        return $this->servicesController->deleteService($id);
    }
    //Contacts
    public function insertContact(Request $request,EntityManagerInterface $e)
    {
        
        return $this->contactsController->insert($request,$e);
    }

    public function getContacts()
    {
        return $this->contactsController->indexContact();
    }

    public function deleteContact(Contacts $contact)
    {
        return $this->contactsController->deleteContact($contact);
    }

    //Horaires
    public function getHoraires()
    {
        return $this->horairesOuvertureController->indexHoraire();
    }
    public function createHoraire(Request $request)
    {
        return $this->horairesOuvertureController->create($request);
    }

    public function editHoraire(Request $request,Horairesouverture $horaire)
    {
        return $this->horairesOuvertureController->update($request,$horaire);
    }

    public function deleteHoraire(int $id)
    {
        return $this->horairesOuvertureController->deleteHoraire($id);
    }

    //Temoignages
    public function getTemoignages()
    {
        return $this->temoignagesController->indexTemoignages();
    }
    public function createTemoignage(Request $request)
    {
        return $this->temoignagesController->create($request);
    }

    //Voitures
    public function getVoitures()
    {
        return $this->voituresOccasionController->indexVoitures();
    }

    public function createVoiture(Request $request)
    {
        return $this->voituresOccasionController->create($request);
    }

    public function editVoiture(Request $request,string $id)
    {
        return $this->voituresOccasionController->update($request,$id);
    }

    public function deleteVoiture(Voituresoccasion $voiture)
    {
        return $this->voituresOccasionController->deleteVoiture($voiture);
    }
    //Jour

    public function getJour()
    {
        return $this->jourSemaineController->listeJoursSemaine();
    }
}
