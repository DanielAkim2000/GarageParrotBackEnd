<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactsType;
use App\Entity\Voituresoccasion;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contacts')]
class ContactsController extends AbstractController
{
    #[Route('/', name: 'app_contacts_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $contacts = $entityManager
            ->getRepository(Contacts::class)
            ->findAll();

        return $this->render('contacts/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/new', name: 'app_contacts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contacts();
        $form = $this->createForm(ContactsType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('app_contacts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contacts/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contacts_show', methods: ['GET'])]
    public function show(Contacts $contact): Response
    {
        return $this->render('contacts/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contacts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contacts $contact, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactsType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contacts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contacts/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contacts_delete', methods: ['POST'])]
    public function delete(Request $request, Contacts $contact, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contacts_index', [], Response::HTTP_SEE_OTHER);
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
}
