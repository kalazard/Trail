<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parcours
 *
 * @ORM\Table(name="parcours", indexes={@ORM\Index(name="fk_parcours_2_idx", columns={"itiniraire"}), @ORM\Index(name="fk_parcours_3_idx", columns={"difficulte"}), @ORM\Index(name="IDX_99B1DEE3B26681E", columns={"evenement"})})
 * @ORM\Entity
 */
class Parcours
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
     * @var \Itiniraire
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Itiniraire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="itiniraire", referencedColumnName="id")
     * })
     */
    private $itiniraire;

    /**
     * @var \DifficulteParcours
     *
     * @ORM\ManyToOne(targetEntity="DifficulteParcours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="difficulte", referencedColumnName="id")
     * })
     */
    private $difficulte;



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

    /**
     * Set itiniraire
     *
     * @param \Site\TrailBundle\Entity\Itiniraire $itiniraire
     * @return Parcours
     */
    public function setItiniraire(\Site\TrailBundle\Entity\Itiniraire $itiniraire)
    {
        $this->itiniraire = $itiniraire;

        return $this;
    }

    /**
     * Get itiniraire
     *
     * @return \Site\TrailBundle\Entity\Itiniraire 
     */
    public function getItiniraire()
    {
        return $this->itiniraire;
    }

    /**
     * Set difficulte
     *
     * @param \Site\TrailBundle\Entity\DifficulteParcours $difficulte
     * @return Parcours
     */
    public function setDifficulte(\Site\TrailBundle\Entity\DifficulteParcours $difficulte = null)
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    /**
     * Get difficulte
     *
     * @return \Site\TrailBundle\Entity\DifficulteParcours 
     */
    public function getDifficulte()
    {
        return $this->difficulte;
    }
}
