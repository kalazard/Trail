<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeLieu
 *
 * @ORM\Table(name="type_lieu", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_type_lieu_1_idx", columns={"icone"})})
 * @ORM\Entity
 */
class TypeLieu
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
     * @ORM\Column(name="label", type="string", length=45, nullable=false)
     */
    private $label;

    /**
     * @var \Icone
     *
     * @ORM\ManyToOne(targetEntity="Icone")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="icone", referencedColumnName="id")
     * })
     */
    private $icone;



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
     * Set label
     *
     * @param string $label
     * @return TypeLieu
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

    /**
     * Set icone
     *
     * @param \Site\TrailBundle\Entity\Icone $icone
     * @return TypeLieu
     */
    public function setIcone(\Site\TrailBundle\Entity\Icone $icone = null)
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * Get icone
     *
     * @return \Site\TrailBundle\Entity\Icone 
     */
    public function getIcone()
    {
        return $this->icone;
    }
}
