<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Itineraire
 *
 * @ORM\Table(name="itineraire", indexes={@ORM\Index(name="fk_itineraire_difficulte_idx", columns={"difficulte"}), @ORM\Index(name="fk_itineraire_1_idx", columns={"auteur"})})
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
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var \Membre
     *
     * @ORM\ManyToOne(targetEntity="Membre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="auteur", referencedColumnName="id")
     * })
     */
    private $auteur;

    /**
     * @var \Difficulteparcours
     *
     * @ORM\ManyToOne(targetEntity="Difficulteparcours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="difficulte", referencedColumnName="id")
     * })
     */
    private $difficulte;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Commentaire", mappedBy="itineraire")
     */
    private $commentaire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Membre", mappedBy="itinerairefavoris")
     */
    private $membres;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Membre", mappedBy="itineraire")
     */
    private $membre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Evenement", mappedBy="itineraire")
     */
    private $evenement;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commentaire = new \Doctrine\Common\Collections\ArrayCollection();
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->membre = new \Doctrine\Common\Collections\ArrayCollection();
        $this->evenement = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * @param \Site\TrailBundle\Entity\Membre $auteur
     * @return Itineraire
     */
    public function setAuteur(\Site\TrailBundle\Entity\Membre $auteur = null)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \Site\TrailBundle\Entity\Membre 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set difficulte
     *
     * @param \Site\TrailBundle\Entity\Difficulteparcours $difficulte
     * @return Itineraire
     */
    public function setDifficulte(\Site\TrailBundle\Entity\Difficulteparcours $difficulte = null)
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    /**
     * Get difficulte
     *
     * @return \Site\TrailBundle\Entity\Difficulteparcours 
     */
    public function getDifficulte()
    {
        return $this->difficulte;
    }

    /**
     * Add commentaire
     *
     * @param \Site\TrailBundle\Entity\Commentaire $commentaire
     * @return Itineraire
     */
    public function addCommentaire(\Site\TrailBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaire[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \Site\TrailBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\Site\TrailBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaire->removeElement($commentaire);
    }

    /**
     * Get commentaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Add membres
     *
     * @param \Site\TrailBundle\Entity\Membre $membres
     * @return Itineraire
     */
    public function addMembre(\Site\TrailBundle\Entity\Membre $membres)
    {
        $this->membres[] = $membres;

        return $this;
    }

    /**
     * Remove membres
     *
     * @param \Site\TrailBundle\Entity\Membre $membres
     */
    public function removeMembre(\Site\TrailBundle\Entity\Membre $membres)
    {
        $this->membres->removeElement($membres);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembres()
    {
        return $this->membres;
    }

    /**
     * Get membre
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * Add evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Itineraire
     */
    public function addEvenement(\Site\TrailBundle\Entity\Evenement $evenement)
    {
        $this->evenement[] = $evenement;

        return $this;
    }

    /**
     * Remove evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     */
    public function removeEvenement(\Site\TrailBundle\Entity\Evenement $evenement)
    {
        $this->evenement->removeElement($evenement);
    }

    /**
     * Get evenement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvenement()
    {
        return $this->evenement;
    }
}
