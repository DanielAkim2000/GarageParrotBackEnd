<?php

namespace App\Entity;

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
     * @ORM\Column(name="id", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="voituresoccasion_id_seq", allocationSize=1, initialValue=1)
     */
    private $id = 'uuid_generate_v4()';

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=255, nullable=false)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255, nullable=false)
     */
    private $modele;

    /**
     * @var int
     *
     * @ORM\Column(name="annee_mise_en_circulation", type="integer", nullable=false)
     */
    private $anneeMiseEnCirculation;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="kilometrage", type="integer", nullable=false)
     */
    private $kilometrage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="administrateur_id", referencedColumnName="id")
     * })
     */
    private $administrateur;


}
