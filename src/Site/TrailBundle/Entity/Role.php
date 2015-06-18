<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use JsonSerializable;
/**
 * Role
 *
 * @ORM\Table(name="role", uniqueConstraints={@ORM\UniqueConstraint(name="label_UNIQUE", columns={"label"})})
 * @ORM\Entity
 */
class Role implements RoleInterface,  JsonSerializable
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
     * @var integer
     *
     * @ORM\Column(name="niveau", type="integer", nullable=true)
     */
    private $niveau;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="role")
     * @ORM\JoinTable(name="role_has_permission",
     *   joinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     *   }
     * )
     */
    private $permission;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set label
     *
     * @param string $label
     * @return Role
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
     * Set niveau
     *
     * @param integer $niveau
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
     * @return integer 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Add permission
     *
     * @param \Site\TrailBundle\Entity\Permission $permission
     * @return Role
     */
    public function addPermission(\Site\TrailBundle\Entity\Permission $permission)
    {
        $this->permission[] = $permission;

        return $this;
    }

    /**
     * Remove permission
     *
     * @param \Site\TrailBundle\Entity\Permission $permission
     */
    public function removePermission(\Site\TrailBundle\Entity\Permission $permission)
    {
        $this->permission->removeElement($permission);
    }

    /**
     * Get permission
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPermission()
    {
        return $this->permission;
    }
	
	public function getRole() {
        return array("ROLE_".$this->getLabel());
    }
	
	public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'name'=> $this->getLabel(),
        );
    }
}
