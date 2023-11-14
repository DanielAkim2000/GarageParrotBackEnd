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
 * @ORM\Table(name="horairesouverture")
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourSemaine(): ?JourSemaine
    {
        if ($this->jourSemaine == NULL) {
            return NULL;
        } else {
            return $this->jourSemaine;
        }
    }

    public function setJourSemaine($jourSemaine): static
    {
        $this->jourSemaine = $jourSemaine;

        return $this;
    }

    public function getHeureOuverture(): ?DateTimeInterface
    {
        return $this->heureOuverture;
    }
    public function setHeureOuverture(\DateTime $heureOuverture): static
    {
        $heure = new \DateTime($heureOuverture->format('H:i:00'));
        $this->heureOuverture = $heure;
        return $this;
    }

    public function getHeureFermeture(): ?DateTimeInterface
    {
        return $this->heureFermeture;
    }
    public function getHeureOuvertureFormatted(): ?string
    {
        if ($this->heureOuverture !== null) {
            return $this->heureOuverture->format('H:i');
        }

        return null;
    }
    public function getHeureFermetureFormatted(): ?string
    {
        if ($this->heureFermeture !== null) {
            return $this->heureFermeture->format('H:i');
        }

        return null;
    }
    public function getJourSemaineFormatted(): ?string
    {
        $jourSemaine = $this->jourSemaine;

        if ($jourSemaine !== null) {
            return $jourSemaine->getId();
        }

        return null;
    }

    public function setHeureFermeture(\DateTime $heureFermeture): static
    {
        $heure = new \DateTime($heureFermeture->format('H:i:00'));
        $this->heureFermeture = $heure;

        return $this;
    }
}
