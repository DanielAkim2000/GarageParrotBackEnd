<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Temoignages
 *
 * @ORM\Table(name="temoignages", indexes={@ORM\Index(name="IDX_840C8612FB88E14F", columns={"utilisateur_id"})})
 * @ORM\Entity
 */
class Temoignages
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="temoignages_id_seq", allocationSize=1, initialValue=1)
     * @Groups("Temoignages")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     * @Groups("Temoignages")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=false)
     * @Groups("Temoignages")
     */
    private $commentaire;

    /**
     * @var int|null
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     * @Groups("Temoignages")
     */
    private $note;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="modere", type="boolean", nullable=true)
     * @Groups("Temoignages")
     */
    private $modere = false;

    /**
     * @var Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     * })
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function isModere(): ?bool
    {
        return $this->modere;
    }

    public function setModere(?bool $modere): static
    {
        $this->modere = $modere;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateurs $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }


}
