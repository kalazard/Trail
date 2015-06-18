<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news", indexes={@ORM\Index(name="fk_news_membre1_idx", columns={"auteur"}), @ORM\Index(name="fk_news_image1_idx", columns={"image"})})
 * @ORM\Entity
 */
class News
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var integer
     *
     * @ORM\Column(name="visibilite", type="integer", nullable=false)
     */
    private $visibilite;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=false)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text", nullable=false)
     */
    private $texte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \Image
     *
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image", referencedColumnName="id")
     * })
     */
    private $image;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Commentaire", inversedBy="new")
     * @ORM\JoinTable(name="commentairenews",
     *   joinColumns={
     *     @ORM\JoinColumn(name="new", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="commentaire", referencedColumnName="id")
     *   }
     * )
     */
    private $commentaire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commentaire = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     * @return News
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
     * Set visibilite
     *
     * @param integer $visibilite
     * @return News
     */
    public function setVisibilite($visibilite)
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    /**
     * Get visibilite
     *
     * @return integer 
     */
    public function getVisibilite()
    {
        return $this->visibilite;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return News
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return News
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return News
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
     * Set image
     *
     * @param \Site\TrailBundle\Entity\Image $image
     * @return News
     */
    public function setImage(\Site\TrailBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Site\TrailBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set auteur
     *
     * @param \Site\TrailBundle\Entity\Membre $auteur
     * @return News
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
     * Add commentaire
     *
     * @param \Site\TrailBundle\Entity\Commentaire $commentaire
     * @return News
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
     * @see \Serializable::unserialize()
     */
    //de meme
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->titre,
            $this->visibilite,
			$this->alias,
			$this->texte,
			$this->image,
			$this->auteur,
			$this->commentaire
        ) = unserialize($serialized);
    }
    
    public function jsonSerialize() {
        return [
            'id' => $this->getId(),
            'titre' => $this->getTitre(),
            'visibilite' => $this->getVisibilite(),
            'alias' => $this->getAlias(),
            'texte' => $this->getTexte(),
            'date' => $this->getDate(),
            'image' => $this->getImage(),
            'auteur' => $this->getAuteur(),
            'commentaire' => $this->getCommentaire()
        ];
    }
}
