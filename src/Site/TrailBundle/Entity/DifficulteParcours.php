<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DifficulteParcours
 *
 * @ORM\Table(name="difficulte_parcours", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class DifficulteParcours
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
     * @ORM\Column(name="niveau_difficulte", type="integer", nullable=false)
     */
    private $niveauDifficulte;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=45, nullable=false)
     */
    private $label;



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
     * Set niveauDifficulte
     *
     * @param integer $niveauDifficulte
     * @return DifficulteParcours
     */
    public function setNiveauDifficulte($niveauDifficulte)
    {
        $this->niveauDifficulte = $niveauDifficulte;

        return $this;
    }

    /**
     * Get niveauDifficulte
     *
     * @return integer 
     */
    public function getNiveauDifficulte()
    {
        return $this->niveauDifficulte;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return DifficulteParcours
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }
}
