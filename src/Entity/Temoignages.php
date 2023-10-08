<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=false)
     */
    private $commentaire;

    /**
     * @var int|null
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="moderé", type="boolean", nullable=true)
     */
    private $moderé = false;

    /**
     * @var Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     * })
     */
    private $utilisateur;


}
