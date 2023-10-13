<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Services
 *
 * @ORM\Table(name="services", indexes={@ORM\Index(name="IDX_7332E1697EE5403C", columns={"administrateur_id"})})
 * @ORM\Entity
 */
class Services
{
    /**
     * @var int
     *
     * @Groups("services")
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="services_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     * @Groups("services") 
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string|null
     * @Groups("services")
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @Groups("services") 
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="administrateur_id", referencedColumnName="id")
     * })
     */
    private $administrateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getImage():string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

}
