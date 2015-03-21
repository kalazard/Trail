<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaireitineraire
 *
 * @ORM\Table(name="commentaireitineraire", indexes={@ORM\Index(name="fk_commentaireitineraire_commentaore_idx", columns={"commentaire"})})
 * @ORM\Entity
 */
class Commentaireitineraire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="itineraire", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $itineraire;

    /**
     * @var \Commentaire
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Commentaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commentaire", referencedColumnName="id")
     * })
     */
    private $commentaire;



    /**
     * Set itineraire
     *
     * @param integer $itineraire
     * @return Commentaireitineraire
     */
    public function setItineraire($itineraire)
    {
        $this->itineraire = $itineraire;

        return $this;
    }

    /**
     * Get itineraire
     *
     * @return integer 
     */
    public function getItineraire()
    {
        return $this->itineraire;
    }

    /**
     * Set commentaire
     *
     * @param \Site\TrailBundle\Entity\Commentaire $commentaire
     * @return Commentaireitineraire
     */
    public function setCommentaire(\Site\TrailBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return \Site\TrailBundle\Entity\Commentaire 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
}
