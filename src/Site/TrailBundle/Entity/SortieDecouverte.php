<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SortieDecouverte
 *
 * @ORM\Table(name="sortie_decouverte", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_sortie_decouverte_1_idx", columns={"evenement"}), @ORM\Index(name="fk_sortie_decouverte_2_idx", columns={"lieu_rendez_vous"})})
 * @ORM\Entity
 */
class SortieDecouverte
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
     * @var \LieuRendezVous
     *
     * @ORM\ManyToOne(targetEntity="LieuRendezVous")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lieu_rendez_vous", referencedColumnName="id")
     * })
     */
    private $lieuRendezVous;



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
     * @return SortieDecouverte
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
     * Set lieuRendezVous
     *
     * @param \Site\TrailBundle\Entity\LieuRendezVous $lieuRendezVous
     * @return SortieDecouverte
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
}
