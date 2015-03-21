<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favoris
 *
 * @ORM\Table(name="favoris", indexes={@ORM\Index(name="fk_favoris_membre_idx", columns={"membres"})})
 * @ORM\Entity
 */
class Favoris
{
    /**
     * @var integer
     *
     * @ORM\Column(name="itinerairefavoris", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $itinerairefavoris;

    /**
     * @var \Membre
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Membre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="membres", referencedColumnName="id")
     * })
     */
    private $membres;



    /**
     * Set itinerairefavoris
     *
     * @param integer $itinerairefavoris
     * @return Favoris
     */
    public function setItinerairefavoris($itinerairefavoris)
    {
        $this->itinerairefavoris = $itinerairefavoris;

        return $this;
    }

    /**
     * Get itinerairefavoris
     *
     * @return integer 
     */
    public function getItinerairefavoris()
    {
        return $this->itinerairefavoris;
    }

    /**
     * Set membres
     *
     * @param \Site\TrailBundle\Entity\Membre $membres
     * @return Favoris
     */
    public function setMembres(\Site\TrailBundle\Entity\Membre $membres)
    {
        $this->membres = $membres;

        return $this;
    }

    /**
     * Get membres
     *
     * @return \Site\TrailBundle\Entity\Membre 
     */
    public function getMembres()
    {
        return $this->membres;
    }
}
