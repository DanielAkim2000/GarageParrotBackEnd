<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\JourSemaine;
use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups("horaires")
     */
    private $jourSemaine;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_ouverture", type="time", nullable=false)
     * @Groups("horaires")
     */
    private $heureOuverture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_fermeture", type="time", nullable=false)
     * @Groups("horaires")
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

    public function getHeureOuverture(): ?string
    {
        return $this->heureOuverture->format('H:i');
    }

    public function setHeureOuverture(\DateTime $heureOuverture): static
    {
        $heure = new \DateTime($heureOuverture->format('H:i:00'));
        $this->heureOuverture = $heure;

        return $this;
    }

    public function getHeureFermeture(): ?string
    {
        return $this->heureFermeture->format('H:i');
    }

    public function setHeureFermeture(\DateTime $heureFermeture): static
    {
        $heure = new \DateTime($heureFermeture->format('H:i:00'));
        $this->heureFermeture = $heure;

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
