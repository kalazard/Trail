<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class Participation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_inscription", type="string", length=45, nullable=true)
     */
    private $etatInscription;

    /**
     * @var string
     *
     * @ORM\Column(name="resulat", type="string", length=45, nullable=true)
     */
    private $resulat;

    /**
     * @var string
     *
     * @ORM\Column(name="divers", type="string", length=45, nullable=true)
     */
    private $divers;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set etatInscription
     *
     * @param string $etatInscription
     * @return Participation
     */
    public function setEtatInscription($etatInscription)
    {
        $this->etatInscription = $etatInscription;

        return $this;
    }

    /**
     * Get etatInscription
     *
     * @return string 
     */
    public function getEtatInscription()
    {
        return $this->etatInscription;
    }

    /**
     * Set resulat
     *
     * @param string $resulat
     * @return Participation
     */
    public function setResulat($resulat)
    {
        $this->resulat = $resulat;

        return $this;
    }

    /**
     * Get resulat
     *
     * @return string 
     */
    public function getResulat()
    {
        return $this->resulat;
    }

    /**
     * Set divers
     *
     * @param string $divers
     * @return Participation
     */
    public function setDivers($divers)
    {
        $this->divers = $divers;

        return $this;
    }

    /**
     * Get divers
     *
     * @return string 
     */
    public function getDivers()
    {
        return $this->divers;
    }
}
