<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="fk_commentaire_auteur_idx", columns={"auteur"})})
 * @ORM\Entity
 */
class Commentaire
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
     * @ORM\Column(name="texte", type="text", nullable=false)
     */
    private $texte;

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
     * @ORM\ManyToMany(targetEntity="Itineraire", inversedBy="commentaire")
     * @ORM\JoinTable(name="commentaireitineraire",
     *   joinColumns={
     *     @ORM\JoinColumn(name="commentaire", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="itineraire", referencedColumnName="id")
     *   }
     * )
     */
    private $itineraire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="News", mappedBy="commentaire")
     */
    private $new;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itineraire = new \Doctrine\Common\Collections\ArrayCollection();
        $this->new = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set texte
     *
     * @param string $texte
     * @return Commentaire
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
     * Set auteur
     *
     * @param \Site\TrailBundle\Entity\Membre $auteur
     * @return Commentaire
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
     * Add itineraire
     *
     * @param \Site\TrailBundle\Entity\Itineraire $itineraire
     * @return Commentaire
     */
    public function addItineraire(\Site\TrailBundle\Entity\Itineraire $itineraire)
    {
        $this->itineraire[] = $itineraire;

        return $this;
    }

    /**
     * Remove itineraire
     *
     * @param \Site\TrailBundle\Entity\Itineraire $itineraire
     */
    public function removeItineraire(\Site\TrailBundle\Entity\Itineraire $itineraire)
    {
        $this->itineraire->removeElement($itineraire);
    }

    /**
     * Get itineraire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItineraire()
    {
        return $this->itineraire;
    }

    /**
     * Add new
     *
     * @param \Site\TrailBundle\Entity\News $new
     * @return Commentaire
     */
    public function addNew(\Site\TrailBundle\Entity\News $new)
    {
        $this->new[] = $new;

        return $this;
    }

    /**
     * Remove new
     *
     * @param \Site\TrailBundle\Entity\News $new
     */
    public function removeNew(\Site\TrailBundle\Entity\News $new)
    {
        $this->new->removeElement($new);
    }

    /**
     * Get new
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNew()
    {
        return $this->new;
    }
}
