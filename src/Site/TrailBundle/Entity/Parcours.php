<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parcours
 *
 * @ORM\Table(name="parcours", indexes={@ORM\Index(name="fk_parcours_evenement_idx", columns={"evenement"})})
 * @ORM\Entity
 */
class Parcours
{
    /**
     * @var integer
     *
     * @ORM\Column(name="itineraire", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $itineraire;

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
     * Set itineraire
     *
     * @param integer $itineraire
     * @return Parcours
     */
    public function setItineraire($itineraire)
    {
        $this->itineraire = $itineraire;

        return $this;
    }

    /**
     * Get itineraire
     *
     * @return integer 
     */
    public function getItineraire()
    {
        return $this->itineraire;
    }

    /**
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Parcours
     */
    public function setEvenement(\Site\TrailBundle\Entity\Evenement $evenement)
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
}
