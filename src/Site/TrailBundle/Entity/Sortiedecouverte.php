<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sortiedecouverte
 *
 * @ORM\Table(name="sortiedecouverte", indexes={@ORM\Index(name="fk_sortiedecouverte_rdv_idx", columns={"lieurendezvous"}), @ORM\Index(name="fk_sortiedecouverte_evenement_idx", columns={"evenement"})})
 * @ORM\Entity
 */
class Sortiedecouverte
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
     * @var \Lieurendezvous
     *
     * @ORM\ManyToOne(targetEntity="Lieurendezvous")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lieurendezvous", referencedColumnName="id")
     * })
     */
    private $lieurendezvous;

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
     * Set lieurendezvous
     *
     * @param \Site\TrailBundle\Entity\Lieurendezvous $lieurendezvous
     * @return Sortiedecouverte
     */
    public function setLieurendezvous(\Site\TrailBundle\Entity\Lieurendezvous $lieurendezvous = null)
    {
        $this->lieurendezvous = $lieurendezvous;

        return $this;
    }

    /**
     * Get lieurendezvous
     *
     * @return \Site\TrailBundle\Entity\Lieurendezvous 
     */
    public function getLieurendezvous()
    {
        return $this->lieurendezvous;
    }

    /**
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Sortiedecouverte
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
}
