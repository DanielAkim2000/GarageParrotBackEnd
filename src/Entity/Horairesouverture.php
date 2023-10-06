<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Horairesouverture
 *
 * @ORM\Table(name="horairesouverture", indexes={@ORM\Index(name="IDX_8C1CC7CB7EE5403C", columns={"administrateur_id"})})
 * @ORM\Entity
 */
class Horairesouverture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="horairesouverture_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var jour_semaine
     *
     * @ORM\Column(name="jour_semaine", type="jour_semaine", nullable=false)
     */
    private $jourSemaine;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_ouverture", type="time", nullable=false)
     */
    private $heureOuverture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_fermeture", type="time", nullable=false)
     */
    private $heureFermeture;

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
