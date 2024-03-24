<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactsType;
use App\Entity\Voituresoccasion;
use App\Service\ImageManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactsController extends AbstractController
{

    private $entityManager;
    private $validator;

    private $imageManager;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator,ImageManager $imageManager)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->imageManager = $imageManager;
    }

    public function indexContact(): Response
    {
        $contacts = $this->entityManager->getRepository(Contacts::class)->findAll();
        $data = [];

        foreach ($contacts as $contact) {
            if($contact->getVoiture()!=null){
                $imageLink = $this->imageManager->generateImageLink($contact->getVoiture()->getImagePath());
            }
            else{
                $imageLink = null;
            }
            $data[] = [
                'id' => $contact->getId(),
                'nom' => $contact->getNom(),
                'prenom' => $contact->getPrenom(),
                'email' => $contact->getEmail(),
                'numero_telephone' => $contact->getNumeroTelephone(),
                'message' => $contact->getMessage(),
                'sujet' => $contact->getSujet(),
                'voiture' => $contact->getVoiture() ? [
                    'id' => $contact->getVoiture()->getId(),
                    'marque' => $contact->getVoiture()->getMarque(),
                    'modele' => $contact->getVoiture()->getModele(),
                    'annee_mise_en_circulation' => $contact->getVoiture()->getAnneeMiseEnCirculation(),
                    'image' => $imageLink
                ] : null,
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function insert(Request $request, EntityManagerInterface $entityManager): Response
    {
        try{
            $contactEntity = new Contacts();
            // Recuperation des donnees du formulaire
            $contact = json_decode($request->getContent(), true);
            if (isset($contact['nom'])) {
                $contactEntity->setNom($contact['nom']);
            }
            else{
                throw new Exception('Précisez votre nom');
            }
            if (isset($contact['prenom'])) {
                $contactEntity->setPrenom($contact['prenom']);
            }
            else{
                throw new Exception('Précisez votre prénom');
            }
            if (isset($contact['email'])) {
                $contactEntity->setEmail($contact['email']);
            }
            else{
                throw new Exception('Précisez votre email');
            }
            if (isset($contact['numero_telephone'])) {
                $contactEntity->setNumeroTelephone($contact['numero_telephone']);
            }
            else{
                throw new Exception('Précisez votre numéro de téléphone');
            }
            if (isset($contact['sujet'])) {
                $contactEntity->setSujet($contact['sujet']);
            }
            else{
                throw new Exception('Précisez le sujet de votre message');
            }
            if (isset($contact['message'])) {
                $contactEntity->setMessage($contact['message']);
            }
            else{
                throw new Exception('Précisez votre message');
            }
            if (isset($contact['dataId'])) {
                $voiture = $entityManager->getRepository(Voituresoccasion::class)->find($contact['dataId']);
                $contactEntity->setVoiture($voiture);
            }
            else{
                $contactEntity->setVoiture(null);
            }
            $entityManager->persist($contactEntity);
            $entityManager->flush();
            return $this->json(['message' => 'Votre message a bien été envoyé']);
        }
        catch(Exception $e){
            return $this->json(['message' => $e->getMessage()]);
        }   

    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['nom'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le nom'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['prenom']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le prenom'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['email']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'email'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['message']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le message'], Response::HTTP_BAD_REQUEST);
        }

        $contact = new Contacts();
        $contact->setNom($data['nom']);
        $contact->setPrenom($data['prenom']);
        $contact->setEmail($data['email']);
        $contact->setNumeroTelephone($data['numero_telephone'] ?? null);
        $contact->setMessage($data['message']);
        $contact->setSujet($data['sujet'] ?? null);

        if (!empty($data['voiture_id'])) {
            $voiture = $this->entityManager->getRepository(Voituresoccasion::class)->find($data['voiture_id']);

            if (!$voiture) {
                return new JsonResponse(['error' => 'Voiture non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $contact->setVoiture($voiture);
        }

        $errors = $this->validator->validate($contact);

        if (count($errors) === 0) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $data = [
                'id' => $contact->getId(),
                'nom' => $contact->getNom(),
                'prenom' => $contact->getPrenom(),
                'email' => $contact->getEmail(),
                'numero_telephone' => $contact->getNumeroTelephone(),
                'message' => $contact->getMessage(),
                'sujet' => $contact->getSujet(),
                'voiture' => $contact->getVoiture() ? [
                    'id' => $voiture->getId(),
                    'marque' => $voiture->getMarque(),
                    'modele' => $voiture->getModele(),
                    'annee_mise_en_circulation' => $voiture->getAnneeMiseEnCirculation(),
                ] : null,
            ];

            return new JsonResponse($data, Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, Contacts $contact): Response
    {
        if (!$contact) {
            return new JsonResponse(['error' => 'Contact non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['nom'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le nom'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['prenom']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le prenom'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['email']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez l\'email'], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['message']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le message'], Response::HTTP_BAD_REQUEST);
        }

        $contact->setNom($data['nom']);
        $contact->setPrenom($data['prenom']);
        $contact->setEmail($data['email']);
        $contact->setNumeroTelephone($data['numero_telephone'] ?? null);
        $contact->setMessage($data['message']);
        $contact->setSujet($data['sujet'] ?? null);

        if (!empty($data['voiture_id'])) {
            $voiture = $this->entityManager->getRepository(Voituresoccasion::class)->find($data['voiture_id']);

            if (!$voiture) {
                return new JsonResponse(['error' => 'Voiture non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $contact->setVoiture($voiture);
        }

        $errors = $this->validator->validate($contact);

        if (count($errors) === 0) {
            $this->entityManager->flush();

            $data = [
                'id' => $contact->getId(),
                'nom' => $contact->getNom(),
                'prenom' => $contact->getPrenom(),
                'email' => $contact->getEmail(),
                'numero_telephone' => $contact->getNumeroTelephone(),
                'message' => $contact->getMessage(),
                'sujet' => $contact->getSujet(),
                'voiture' => $contact->getVoiture() ? [
                    'id' => $voiture->getId(),
                    'marque' => $voiture->getMarque(),
                    'modele' => $voiture->getModele(),
                    'annee_mise_en_circulation' => $voiture->getAnneeMiseEnCirculation(),
                ] : null,
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteContact(Contacts $contact): Response
    {
        if (!$contact) {
            return new JsonResponse(['error' => 'Contact non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($contact);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
