<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Evenementitineraire
 *
 * @ORM\Table(name="evenementitineraire", indexes={@ORM\Index(name="fk_evenementitineraire_evenement_idx", columns={"evenement"})})
 * @ORM\Entity
 */
class Evenementitineraire implements JsonSerializable
{
    /**
     * @var \Evenement
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evenement", referencedColumnName="id")
     * })
     */
    private $evenement;

    /**
     * @var integer
     *
     * @ORM\Column(name="iditineraire", type="integer", nullable=false)
     * @ORM\Id
     * 
     */
    private $idItineraire;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nomitineraire", type="string", length=255, nullable=false)
     */
    private $nomItineraire;

    /**
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Evenement
     */
    public function setEvenement(\Site\TrailBundle\Entity\Evenement $evenement = null)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return \Site\TrailBundle\Entity\Evenement 
     */
    public function getEvenement()
    {
        return $this->evenement;
    }
    
    /**
     * Set idItineraire
     *
     * @param integer $idItineraire
     * @return integer
     */
    public function setIdItineraire($idItineraire)
    {
        $this->idItineraire = $idItineraire;

        return $this;
    }

    /**
     * Get idItineraire
     *
     * @return integer 
     */
    public function getIdItineraire()
    {
        return $this->idItineraire;
    }
    
    /**
     * Set nomItineraire
     *
     * @param string $nomItineraire
     * @return string
     */
    public function setNomItineraire($nomItineraire)
    {
        $this->nomItineraire = $nomItineraire;

        return $this;
    }

    /**
     * Get nomItineraire
     *
     * @return string 
     */
    public function getNomItineraire()
    {
        return $this->nomItineraire;
    }    
    
    public function jsonSerialize() {
        return [
            '$evenement' => $this->getEvenement(),
            '$idItineraire' => $this->getIdItineraire(),
            '$nomItineraire' => $this->getNomItineraire()
        ];
    }
}