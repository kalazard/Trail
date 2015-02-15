<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrainement
 *
 * @ORM\Table(name="entrainement", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_entrainement_1_idx", columns={"lieu_rendez_vous"}), @ORM\Index(name="fk_entrainement_2_idx", columns={"programme"}), @ORM\Index(name="fk_entrainement_3_idx", columns={"evenement"})})
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
     * @var \LieuRendezVous
     *
     * @ORM\ManyToOne(targetEntity="LieuRendezVous")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lieu_rendez_vous", referencedColumnName="id")
     * })
     */
    private $lieuRendezVous;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lieuRendezVous
     *
     * @param \Site\TrailBundle\Entity\LieuRendezVous $lieuRendezVous
     * @return Entrainement
     */
    public function setLieuRendezVous(\Site\TrailBundle\Entity\LieuRendezVous $lieuRendezVous = null)
    {
        $this->lieuRendezVous = $lieuRendezVous;

        return $this;
    }

    /**
     * Get lieuRendezVous
     *
     * @return \Site\TrailBundle\Entity\LieuRendezVous 
     */
    public function getLieuRendezVous()
    {
        return $this->lieuRendezVous;
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
}
