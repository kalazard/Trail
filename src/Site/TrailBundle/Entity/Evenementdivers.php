<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Evenementdivers
 *
 * @ORM\Table(name="evenementdivers", indexes={@ORM\Index(name="fk_evenementdivers_evenement_idx", columns={"evenement"})})
 * @ORM\Entity
 */
class Evenementdivers implements JsonSerializable
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
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

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
     * Set description
     *
     * @param string $description
     * @return Evenementdivers
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Evenementdivers
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
            'description' => $this->getDescription(),
            'evenement' => $this->getEvenement() 
        ];
    }
}
