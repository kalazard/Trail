<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Itineraire
 *
 * @ORM\Table(name="itineraire")
 * @ORM\Entity
 */
class Itineraire
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
     * @ORM\Column(name="trace", type="integer", nullable=false)
     */
    private $trace;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreation", type="date", nullable=false)
     */
    private $datecreation;

    /**
     * @var float
     *
     * @ORM\Column(name="longueur", type="float", precision=10, scale=0, nullable=false)
     */
    private $longueur;

    /**
     * @var float
     *
     * @ORM\Column(name="deniveleplus", type="float", precision=10, scale=0, nullable=false)
     */
    private $deniveleplus;

    /**
     * @var float
     *
     * @ORM\Column(name="denivelemoins", type="float", precision=10, scale=0, nullable=false)
     */
    private $denivelemoins;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="typechemin", type="string", length=255, nullable=false)
     */
    private $typechemin;

    /**
     * @var integer
     *
     * @ORM\Column(name="difficulte", type="integer", nullable=false)
     */
    private $difficulte;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="auteur", type="integer", nullable=false)
     */
    private $auteur;



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
     * Set trace
     *
     * @param integer $trace
     * @return Itineraire
     */
    public function setTrace($trace)
    {
        $this->trace = $trace;

        return $this;
    }

    /**
     * Get trace
     *
     * @return integer 
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * Set datecreation
     *
     * @param \DateTime $datecreation
     * @return Itineraire
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime 
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Set longueur
     *
     * @param float $longueur
     * @return Itineraire
     */
    public function setLongueur($longueur)
    {
        $this->longueur = $longueur;

        return $this;
    }

    /**
     * Get longueur
     *
     * @return float 
     */
    public function getLongueur()
    {
        return $this->longueur;
    }

    /**
     * Set deniveleplus
     *
     * @param float $deniveleplus
     * @return Itineraire
     */
    public function setDeniveleplus($deniveleplus)
    {
        $this->deniveleplus = $deniveleplus;

        return $this;
    }

    /**
     * Get deniveleplus
     *
     * @return float 
     */
    public function getDeniveleplus()
    {
        return $this->deniveleplus;
    }

    /**
     * Set denivelemoins
     *
     * @param float $denivelemoins
     * @return Itineraire
     */
    public function setDenivelemoins($denivelemoins)
    {
        $this->denivelemoins = $denivelemoins;

        return $this;
    }

    /**
     * Get denivelemoins
     *
     * @return float 
     */
    public function getDenivelemoins()
    {
        return $this->denivelemoins;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Itineraire
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
     * Set nom
     *
     * @param string $nom
     * @return Itineraire
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Itineraire
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set typechemin
     *
     * @param string $typechemin
     * @return Itineraire
     */
    public function setTypechemin($typechemin)
    {
        $this->typechemin = $typechemin;

        return $this;
    }

    /**
     * Get typechemin
     *
     * @return string 
     */
    public function getTypechemin()
    {
        return $this->typechemin;
    }

    /**
     * Set difficulte
     *
     * @param integer $difficulte
     * @return Itineraire
     */
    public function setDifficulte($difficulte)
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    /**
     * Get difficulte
     *
     * @return integer 
     */
    public function getDifficulte()
    {
        return $this->difficulte;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Itineraire
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set auteur
     *
     * @param integer $auteur
     * @return Itineraire
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return integer 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }
}
