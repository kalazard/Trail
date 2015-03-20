<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participants
 *
 * @ORM\Table(name="participants", indexes={@ORM\Index(name="fk_participants_membre_idx", columns={"membre"}), @ORM\Index(name="fk_participants_evenement_idx", columns={"evenement"}), @ORM\Index(name="fk_participants_participation_idx", columns={"participation"})})
 * @ORM\Entity
 */
class Participants
{
    /**
     * @var \Evenement
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evenement", referencedColumnName="id")
     * })
     */
    private $evenement;

    /**
     * @var \Membre
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Membre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="membre", referencedColumnName="id")
     * })
     */
    private $membre;

    /**
     * @var \Participation
     *
     * @ORM\ManyToOne(targetEntity="Participation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="participation", referencedColumnName="id")
     * })
     */
    private $participation;



    /**
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return Participants
     */
    public function setEvenement(\Site\TrailBundle\Entity\Evenement $evenement)
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
     * Set membre
     *
     * @param \Site\TrailBundle\Entity\Membre $membre
     * @return Participants
     */
    public function setMembre(\Site\TrailBundle\Entity\Membre $membre)
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * Get membre
     *
     * @return \Site\TrailBundle\Entity\Membre 
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * Set participation
     *
     * @param \Site\TrailBundle\Entity\Participation $participation
     * @return Participants
     */
    public function setParticipation(\Site\TrailBundle\Entity\Participation $participation = null)
    {
        $this->participation = $participation;

        return $this;
    }

    /**
     * Get participation
     *
     * @return \Site\TrailBundle\Entity\Participation 
     */
    public function getParticipation()
    {
        return $this->participation;
    }
}
