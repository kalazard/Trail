<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntrainementPersonnel
 *
 * @ORM\Table(name="entrainement_personnel", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_entrainement_personnel_1_idx", columns={"evenement"})})
 * @ORM\Entity
 */
class EntrainementPersonnel
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
     * @var \Evenement
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evenement", referencedColumnName="id")
     * })
     */
    private $evenement;



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
     * Set evenement
     *
     * @param \Site\TrailBundle\Entity\Evenement $evenement
     * @return EntrainementPersonnel
     */
    public function setEvenement(\Site\TrailBundle\Entity\Evenement $evenement = null)
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
}
