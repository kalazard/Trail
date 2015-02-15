<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Itiniraire
 *
 * @ORM\Table(name="itiniraire", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_itiniraire_1_idx", columns={"itiniraire"})})
 * @ORM\Entity
 */
class Itiniraire
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="longueur", type="string", length=45, nullable=false)
     */
    private $longueur;

    /**
     * @var string
     *
     * @ORM\Column(name="denivele", type="string", length=45, nullable=false)
     */
    private $denivele;

    /**
     * @var \Gpx
     *
     * @ORM\ManyToOne(targetEntity="Gpx")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="itiniraire", referencedColumnName="id")
     * })
     */
    private $itiniraire;



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
     * Set date
     *
     * @param \DateTime $date
     * @return Itiniraire
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set longueur
     *
     * @param string $longueur
     * @return Itiniraire
     */
    public function setLongueur($longueur)
    {
        $this->longueur = $longueur;

        return $this;
    }

    /**
     * Get longueur
     *
     * @return string 
     */
    public function getLongueur()
    {
        return $this->longueur;
    }

    /**
     * Set denivele
     *
     * @param string $denivele
     * @return Itiniraire
     */
    public function setDenivele($denivele)
    {
        $this->denivele = $denivele;

        return $this;
    }

    /**
     * Get denivele
     *
     * @return string 
     */
    public function getDenivele()
    {
        return $this->denivele;
    }

    /**
     * Set itiniraire
     *
     * @param \Site\TrailBundle\Entity\Gpx $itiniraire
     * @return Itiniraire
     */
    public function setItiniraire(\Site\TrailBundle\Entity\Gpx $itiniraire = null)
    {
        $this->itiniraire = $itiniraire;

        return $this;
    }

    /**
     * Get itiniraire
     *
     * @return \Site\TrailBundle\Entity\Gpx 
     */
    public function getItiniraire()
    {
        return $this->itiniraire;
    }
}
