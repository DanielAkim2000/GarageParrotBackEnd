<?php 

// App\Service\ImageManager.php

namespace App\Service;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageManager
{
    private $parameterBag;
    private $s3Client;

    public function __construct(ParameterBagInterface $parameterBag, S3Client $s3Client)
    {
        $this->parameterBag = $parameterBag;
        $this->s3Client = $s3Client;
    }

    public function upload(File $file): string
    {
        $bucketName = $this->parameterBag->get('app.s3_bucket');
        $uploadDirectory = $this->parameterBag->get('app.upload_directory');
    
        $fileName = uniqid().'.'.$file->guessExtension();
    
        // Utiliser le client AWS S3 pour uploader le fichier dans le compartiment S3
        $this->s3Client->putObject([
            'Bucket' => $bucketName,
            'Key' => $uploadDirectory.'/'.$fileName,
            'Body' => fopen($file->getPathname(), 'r'),
        ]);
    
        return $fileName;
    }

    public function remove(string $fileName): void
    {
        $bucketName = $this->parameterBag->get('app.s3_bucket');
        $uploadDirectory = $this->parameterBag->get('app.upload_directory');

        // Utiliser le client AWS S3 pour supprimer le fichier du compartiment S3
        $this->s3Client->deleteObject([
            'Bucket' => $bucketName,
            'Key' => $uploadDirectory.'/'.$fileName,
        ]);
    }

    public function generateImageLinkk(string $imageName): string
    {
        $imageBaseUrl = $this->parameterBag->get('app.image_base_url');
        return $imageBaseUrl . '/' . $imageName;
    }
    public function generateImageLink(string $imageName): string
    {
        $bucketName = $this->parameterBag->get('app.s3_bucket');
        $uploadDirectory = $this->parameterBag->get('app.upload_directory');

        // Configuration du client S3
        $s3Client = new S3Client([
            'region' => $this->parameterBag->get('aws_s3_region'),
            'version' => 'latest',
            'credentials' => [
                'key' => $this->parameterBag->get('aws_s3_key'),
                'secret' => $this->parameterBag->get('aws_s3_secret'),
            ],
        ]);

        // Génération de l'URL signée avec une validité de 1 heure (3600 secondes)
        try {
            $command = $s3Client->getCommand('GetObject', [
                'Bucket' => $bucketName,
                'Key' => $uploadDirectory.'/'.$imageName,
            ]);
            $request = $s3Client->createPresignedRequest($command, '+1 hour');
            $signedUrl = (string)$request->getUri();
            return $signedUrl;
        } catch (S3Exception $e) {
            // Gestion des erreurs, par exemple, le fichier n'existe pas
            return ''; // ou lancez une exception selon votre besoin
        }
    }
}
