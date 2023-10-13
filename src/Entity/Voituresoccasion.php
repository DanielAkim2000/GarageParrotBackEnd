<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Voituresoccasion
 *
 * @ORM\Table(name="voituresoccasion", indexes={@ORM\Index(name="IDX_253F5FD37EE5403C", columns={"administrateur_id"})})
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
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Groups("VoituresOccasions") 
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups("VoituresOccasions") 
     */
    private $description;

    /**
     * @var Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="administrateur_id", referencedColumnName="id")
     * })
     */
    private $administrateur;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
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

    public function getAdministrateur(): ?Utilisateurs
    {
        return $this->administrateur;
    }

    public function setAdministrateur(?Utilisateurs $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }


}
