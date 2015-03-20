<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
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
     * @ORM\Column(name="etatinscription", type="string", length=45, nullable=false)
     */
    private $etatinscription;

    /**
     * @var string
     *
     * @ORM\Column(name="resultat", type="string", length=45, nullable=false)
     */
    private $resultat;

    /**
     * @var string
     *
     * @ORM\Column(name="divers", type="string", length=45, nullable=false)
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
     * Set etatinscription
     *
     * @param string $etatinscription
     * @return Participation
     */
    public function setEtatinscription($etatinscription)
    {
        $this->etatinscription = $etatinscription;

        return $this;
    }

    /**
     * Get etatinscription
     *
     * @return string 
     */
    public function getEtatinscription()
    {
        return $this->etatinscription;
    }

    /**
     * Set resultat
     *
     * @param string $resultat
     * @return Participation
     */
    public function setResultat($resultat)
    {
        $this->resultat = $resultat;

        return $this;
    }

    /**
     * Get resultat
     *
     * @return string 
     */
    public function getResultat()
    {
        return $this->resultat;
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
