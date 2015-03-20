<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrainement
 *
 * @ORM\Table(name="entrainement", indexes={@ORM\Index(name="fk_entrainement_programme_idx", columns={"programme"}), @ORM\Index(name="fk_entrainement_evenement_idx", columns={"evenement"}), @ORM\Index(name="fk_entrainement_rdv_idx", columns={"lieurendezvous"})})
 * @ORM\Entity
 */
class Entrainement
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
     * @var \Programme
     *
     * @ORM\ManyToOne(targetEntity="Programme")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="programme", referencedColumnName="id")
     * })
     */
    private $programme;

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
     * @var \Lieurendezvous
     *
     * @ORM\ManyToOne(targetEntity="Lieurendezvous")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lieurendezvous", referencedColumnName="id")
     * })
     */
    private $lieurendezvous;



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
     * Set programme
     *
     * @param \Site\TrailBundle\Entity\Programme $programme
     * @return Entrainement
     */
    public function setProgramme(\Site\TrailBundle\Entity\Programme $programme = null)
    {
        $this->programme = $programme;

        return $this;
    }

    /**
     * Get programme
     *
     * @return \Site\TrailBundle\Entity\Programme 
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Entrainement
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
     * Set lieurendezvous
     *
     * @param \Site\TrailBundle\Entity\Lieurendezvous $lieurendezvous
     * @return Entrainement
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
}
