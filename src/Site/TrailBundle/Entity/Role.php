<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class Role
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=45, nullable=false)
     */
    private $niveau;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="MenuItem", inversedBy="role")
     * @ORM\JoinTable(name="acces",
     *   joinColumns={
     *     @ORM\JoinColumn(name="role", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="menu_item", referencedColumnName="id")
     *   }
     * )
     */
    private $menuItem;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menuItem = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set niveau
     *
     * @param string $niveau
     * @return Role
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Add menuItem
     *
     * @param \Site\TrailBundle\Entity\MenuItem $menuItem
     * @return Role
     */
    public function addMenuItem(\Site\TrailBundle\Entity\MenuItem $menuItem)
    {
        $this->menuItem[] = $menuItem;

        return $this;
    }

    /**
     * Remove menuItem
     *
     * @param \Site\TrailBundle\Entity\MenuItem $menuItem
     */
    public function removeMenuItem(\Site\TrailBundle\Entity\MenuItem $menuItem)
    {
        $this->menuItem->removeElement($menuItem);
    }

    /**
     * Get menuItem
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenuItem()
    {
        return $this->menuItem;
    }
}
