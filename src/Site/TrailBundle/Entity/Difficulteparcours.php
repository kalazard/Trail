<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Difficulteparcours
 *
 * @ORM\Table(name="difficulteparcours")
 * @ORM\Entity
 */
class Difficulteparcours
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
     * @var integer
     *
     * @ORM\Column(name="niveau", type="integer", nullable=false)
     */
    private $niveau;

    /**
     * @var string
     *
     * @ORM\Column(name="labal", type="string", length=255, nullable=false)
     */
    private $labal;



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
     * Set niveau
     *
     * @param integer $niveau
     * @return Difficulteparcours
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return integer 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set labal
     *
     * @param string $labal
     * @return Difficulteparcours
     */
    public function setLabal($labal)
    {
        $this->labal = $labal;

        return $this;
    }

    /**
     * Get labal
     *
     * @return string 
     */
    public function getLabal()
    {
        return $this->labal;
    }
}
