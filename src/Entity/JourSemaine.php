<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * JourSemaine
 *
 * @ORM\Table(name="jour_semaine")
 * @ORM\Entity
 */
class JourSemaine
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="jour_semaine_id_seq", allocationSize=1, initialValue=1)
     * @Groups("horaires")
     */
    private $id;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value ;
    }
    
    public function __toString() {
        return $this->id;
    }
}
