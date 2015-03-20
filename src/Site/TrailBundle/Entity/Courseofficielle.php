<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Courseofficielle
 *
 * @ORM\Table(name="courseofficielle", indexes={@ORM\Index(name="fk_courseofficielle_evenement_idx", columns={"evenement"})})
 * @ORM\Entity
 */
class Courseofficielle
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
     * @ORM\Column(name="site_utl", type="string", length=255, nullable=false)
     */
    private $siteUtl;

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
     * Set siteUtl
     *
     * @param string $siteUtl
     * @return Courseofficielle
     */
    public function setSiteUtl($siteUtl)
    {
        $this->siteUtl = $siteUtl;

        return $this;
    }

    /**
     * Get siteUtl
     *
     * @return string 
     */
    public function getSiteUtl()
    {
        return $this->siteUtl;
    }

    /**
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Courseofficielle
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
