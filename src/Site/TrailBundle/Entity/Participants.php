<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participants
 *
 * @ORM\Table(name="participants", indexes={@ORM\Index(name="fk_participants_1_idx", columns={"evenement"}), @ORM\Index(name="fk_participants_3_idx", columns={"participation"}), @ORM\Index(name="IDX_716970928D93D649", columns={"user"})})
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
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

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
     * Set user
     *
     * @param \Site\TrailBundle\Entity\User $user
     * @return Participants
     */
    public function setUser(\Site\TrailBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Site\TrailBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
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
