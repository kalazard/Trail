<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Itiniraire
 *
 * @ORM\Table(name="itiniraire", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_itiniraire_1_idx", columns={"itiniraire"}), @ORM\Index(name="fk_difficulte", columns={"difficulté"})})
 * @ORM\Entity
 */
class Itiniraire implements JsonSerializable
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=45, nullable=true)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="typechemin", type="string", length=45, nullable=true)
     */
    private $typechemin;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=100, nullable=true)
     */
    private $commentaire;

    /**
     * @var \DifficulteParcours
     *
     * @ORM\ManyToOne(targetEntity="DifficulteParcours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="difficulté", referencedColumnName="id")
     * })
     */
    private $difficulté;

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
     * Set nom
     *
     * @param string $nom
     * @return Itiniraire
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
     * @return Itiniraire
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
     * @return Itiniraire
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
     * Set commentaire
     *
     * @param string $commentaire
     * @return Itiniraire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set difficulté
     *
     * @param \Site\TrailBundle\Entity\DifficulteParcours $difficulté
     * @return Itiniraire
     */
    public function setDifficulté(\Site\TrailBundle\Entity\DifficulteParcours $difficulté = null)
    {
        $this->difficulté = $difficulté;

        return $this;
    }

    /**
     * Get difficulté
     *
     * @return \Site\TrailBundle\Entity\DifficulteParcours 
     */
    public function getDifficulté()
    {
        return $this->difficulté;
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

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'date'=> $this->getDate(),
            'longueur'=> $this->getLongueur(),
            'denivele'=> $this->getDenivele(),
            'nom' => $this->getNom(),
            'numero'=> $this->getNumero(),
            'typechemin'=> $this->getTypechemin(),
            'commentaire'=> $this->getCommentaire(),
            'difficulte'=> $this->getDifficulte()
        );
    }
}
