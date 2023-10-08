<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipementsoptions
 *
 * @ORM\Table(name="equipementsoptions", indexes={@ORM\Index(name="IDX_7561C74D181A8BA", columns={"voiture_id"})})
 * @ORM\Entity
 */
class Equipementsoptions
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="equipementsoptions_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var \Voituresoccasion
     *
     * @ORM\ManyToOne(targetEntity="Voituresoccasion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="voiture_id", referencedColumnName="id")
     * })
     */
    private $voiture;


}
