<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Poi
 *
 * @ORM\Table(name="poi", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_poi_1_idx", columns={"coordonnees"}), @ORM\Index(name="fk_poi_2_idx", columns={"lieu"})})
 * @ORM\Entity
 */
class Poi
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
     * @ORM\Column(name="titre", type="string", length=45, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=45, nullable=false)
     */
    private $description;

    /**
     * @var \Coordonnees
     *
     * @ORM\ManyToOne(targetEntity="Coordonnees")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="coordonnees", referencedColumnName="id")
     * })
     */
    private $coordonnees;

    /**
     * @var \TypeLieu
     *
     * @ORM\ManyToOne(targetEntity="TypeLieu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lieu", referencedColumnName="id")
     * })
     */
    private $lieu;



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
     * Set titre
     *
     * @param string $titre
     * @return Poi
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Poi
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
     * Set coordonnees
     *
     * @param \Site\TrailBundle\Entity\Coordonnees $coordonnees
     * @return Poi
     */
    public function setCoordonnees(\Site\TrailBundle\Entity\Coordonnees $coordonnees = null)
    {
        $this->coordonnees = $coordonnees;

        return $this;
    }

    /**
     * Get coordonnees
     *
     * @return \Site\TrailBundle\Entity\Coordonnees 
     */
    public function getCoordonnees()
    {
        return $this->coordonnees;
    }

    /**
     * Set lieu
     *
     * @param \Site\TrailBundle\Entity\TypeLieu $lieu
     * @return Poi
     */
    public function setLieu(\Site\TrailBundle\Entity\TypeLieu $lieu = null)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return \Site\TrailBundle\Entity\TypeLieu 
     */
    public function getLieu()
    {
        return $this->lieu;
    }
}
