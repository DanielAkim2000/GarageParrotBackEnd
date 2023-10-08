<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\JourSemaine;

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
     * @var JourSemaine
     *
     * @ORM\OneToOne(targetEntity="JourSemaine")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="jour_semaine", referencedColumnName="id")
     * })
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

    public function getJourSemaine():?JourSemaine
    {
        if($this->jourSemaine == NULL){
            return NULL;
        }
        else
        {
            return $this->jourSemaine;
        }
    }

    public function setJourSemaine($jourSemaine): static
    {
        $this->jourSemaine = $jourSemaine;

        return $this;
    }

    public function getHeureOuverture(): ?\DateTimeInterface
    {
        return $this->heureOuverture;
    }

    public function setHeureOuverture(\DateTimeInterface $heureOuverture): static
    {
        $this->heureOuverture = $heureOuverture;

        return $this;
    }

    public function getHeureFermeture(): ?\DateTimeInterface
    {
        return $this->heureFermeture;
    }

    public function setHeureFermeture(\DateTimeInterface $heureFermeture): static
    {
        $this->heureFermeture = $heureFermeture;

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
