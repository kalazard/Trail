<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Entrainementpersonnel
 *
 * @ORM\Table(name="entrainementpersonnel", indexes={@ORM\Index(name="fk_entrainementpersonnel_evenement_idx", columns={"evenement"})})
 * @ORM\Entity
 */
class Entrainementpersonnel implements JsonSerializable
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
     * @var \Evenement
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evenement", referencedColumnName="id")
     * })
     */
    private $evenement;



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
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Entrainementpersonnel
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
    
    public function jsonSerialize() {
        return [
            'id' => $this->getId(),
            'evenement' => $this->getEvenement(),
        ];
    }
}
