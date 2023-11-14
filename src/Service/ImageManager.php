<?php 

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageManager
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function upload(File $file): string
    {
        $uploadDir = $this->parameterBag->get('app.upload_directory');
        $fileName = uniqid().'.'.$file->guessExtension();
        $file->move($uploadDir, $fileName);

        return $fileName;
    }

    public function remove(string $fileName): void
    {
        $uploadDir = $this->parameterBag->get('app.upload_directory');
        $filePath = $uploadDir . '/' . $fileName;
        
        $filesystem = new Filesystem();
        if ($filesystem->exists($filePath)) {
            $filesystem->remove($filePath);
        }
    }
    public function generateImageLink(string $imageName): string
    {
        $imageBaseUrl = $this->parameterBag->get('app.image_base_url');
        return $imageBaseUrl . '/' . $imageName;
    }
}
