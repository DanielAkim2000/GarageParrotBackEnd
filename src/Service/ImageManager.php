<?php 

// App\Service\ImageManager.php

namespace App\Service;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
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
        $file->move($uploadDirectory, $fileName);

        // Utiliser le client AWS S3 pour uploader le fichier dans le compartiment S3
        $this->s3Client->putObject([
            'Bucket' => $bucketName,
            'Key' => $uploadDirectory.'/'.$fileName,
            'Body' => fopen($file->getPathname(), 'r'),
            'ACL' => 'public-read', // Cela dépend de vos besoins de sécurité
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

    public function generateImageLink(string $imageName): string
    {
        $imageBaseUrl = $this->parameterBag->get('app.image_base_url');
        return $imageBaseUrl . '/' . $imageName;
    }
}
