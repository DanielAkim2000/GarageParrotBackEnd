<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Voituresoccasion
 *
 * @ORM\Table(name="voituresoccasion")
 * @ORM\Entity
 */
class Voituresoccasion
{
    /**
     * @var string
     * 
     * @ORM\Id
     * 
     * @ORM\Column(type="uuid", unique=true)
     * 
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * 
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     * 
     * @Groups("VoituresOccasions") 
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=255, nullable=false)
     * @Groups("VoituresOccasions") 
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255, nullable=false)
     * @Groups("VoituresOccasions") 
     */
    private $modele;

    /**
     * @var int
     *
     * @ORM\Column(name="annee_mise_en_circulation", type="integer", nullable=false)
     * @Groups("VoituresOccasions") 
     */
    private $anneeMiseEnCirculation;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=false)
     * @Groups("VoituresOccasions") 
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="kilometrage", type="integer", nullable=false)
     * @Groups("VoituresOccasions") 
     */
    private $kilometrage;

     /**
     * @ORM\Column(nullable=true)
     */
    private ?string $imagePath = null; // Remplacez imageName par imagePath
    
    private ?File $imageFile = null;


    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups("VoituresOccasions") 
     */
    private $description;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getAnneeMiseEnCirculation(): ?int
    {
        return $this->anneeMiseEnCirculation;
    }

    public function setAnneeMiseEnCirculation(int $anneeMiseEnCirculation): static
    {
        $this->anneeMiseEnCirculation = $anneeMiseEnCirculation;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
