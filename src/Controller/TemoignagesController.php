<?php

namespace App\Controller;

use App\Entity\Temoignages;
use App\Form\TemoignagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TemoignagesController extends AbstractController
{
    public function getTemoignages(EntityManagerInterface $entityManager): Response
    {
        $temoignages = $entityManager
            ->getRepository(Temoignages::class)
            ->findAll();

        return $this->json([
            'temoignages' => $temoignages,
        ],200,[],['groups'=>'Temoignages']);
    }

    public function insert(Request $request,EntityManagerInterface $entityManager){
        try{
            $temoignagesEntity = new Temoignages();
            $temoignages = json_decode($request->getContent(),true);
            if(isset($temoignages['nom'])){
                $temoignagesEntity->setNom($temoignages['nom']);
            }
            else{
                throw new Exception('Précisez votre nom');
            }
            if(isset($temoignages['note'])){
                $temoignagesEntity->setNote($temoignages['note']);
            }
            else{
                throw new Exception('Laisser une note');
            }
            if(isset($temoignages['commentaire'])){
                $temoignagesEntity->setCommentaire($temoignages['commentaire']);
            }
            else{
                throw new Exception('Laisser une commentaire');
            }
            $entityManager->persist($temoignagesEntity);
            $entityManager->flush();
            return $this->json(['message' => 'Votre avis a bien été envoyé']);
        }
        catch(Exception $e){
            return $this->json(['message' => $e->getMessage()]);
        }
    }

    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function indexTemoignages(): Response
    {
        $temoignages = $this->entityManager->getRepository(Temoignages::class)->findAll();
        $data = [];

        foreach ($temoignages as $temoignage) {
            $data[] = [
                'id' => $temoignage->getId(),
                'nom' => $temoignage->getNom(),
                'commentaire' => $temoignage->getCommentaire(),
                'note' => $temoignage->getNote(),
                'modere' => $temoignage->isModere(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function create(Request $request): Response
    {
        $data = $request->request->all();

        if (!isset($data['commentaire'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le commentaire'], Response::HTTP_BAD_REQUEST);
        }

        if(!isset($data['nom']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le nom'], Response::HTTP_BAD_REQUEST);         
        }

        if(!isset($data['note']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez la note'], Response::HTTP_BAD_REQUEST);         
        }

        $temoignage = new Temoignages();
        $temoignage->setNom($data['nom'] ?? null);
        $temoignage->setCommentaire($data['commentaire']);
        $temoignage->setNote($data['note'] ?? null);
        if($data['modere'] === "false"){
            $temoignage->setModere(false);
        }
        else{
            $temoignage->setModere(true);
        }

        $errors = $this->validator->validate($temoignage);

        if (count($errors) === 0) {
            $this->entityManager->persist($temoignage);
            $this->entityManager->flush();

            $data = [
                'id' => $temoignage->getId(),
                'nom' => $temoignage->getNom(),
                'commentaire' => $temoignage->getCommentaire(),
                'note' => $temoignage->getNote(),
                'modere' => $temoignage->isModere(),
            ];

            return new JsonResponse($data, Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, Temoignages $temoignage): Response
    {
        if (!$temoignage) {
            return new JsonResponse(['error' => 'Témoignage non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();

        if (!isset($data['commentaire'])) {
            return new JsonResponse(['error' => 'Veuillez précisez le commentaire'], Response::HTTP_BAD_REQUEST);
        }

        if(!isset($data['nom']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez le nom'], Response::HTTP_BAD_REQUEST);         
        }

        if(!isset($data['note']))
        {
            return new JsonResponse(['error' => 'Veuillez précisez la note'], Response::HTTP_BAD_REQUEST);         
        }       

        $temoignage->setNom($data['nom'] ?? null);
        $temoignage->setCommentaire($data['commentaire']);
        $temoignage->setNote($data['note'] ?? null);
        if($data['modere'] === "false"){
            $temoignage->setModere(false);
        }
        else{
            $temoignage->setModere(true);
        }

        $errors = $this->validator->validate($temoignage);

        if (count($errors) === 0) {
            $this->entityManager->flush();

            $data = [
                'id' => $temoignage->getId(),
                'nom' => $temoignage->getNom(),
                'commentaire' => $temoignage->getCommentaire(),
                'note' => $temoignage->getNote(),
                'modere' => $temoignage->isModere(),
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
    }
    public function deleteTemoignages(Temoignages $temoignage): Response
    {
        if (!$temoignage) {
            return new JsonResponse(['error' => 'Témoignage non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($temoignage);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
