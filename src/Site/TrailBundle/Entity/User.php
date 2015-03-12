<?php

namespace Site\TrailBundle\Entity;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="username_UNIQUE", columns={"username"})}, indexes={@ORM\Index(name="fk_user_1_idx", columns={"role"})})
 * @ORM\Entity
 */
class User implements \Symfony\Component\Security\Core\User\UserInterface, JsonSerializable
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
     * @ORM\Column(name="email", type="string", length=45, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;


    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=45, nullable=false, unique=true)
     */
    private $username;

    

    /**
     * @var string
     *
     * @ORM\Column(name="tokenics", type="string", length=32, nullable=false)
     */
    private $tokenics;

    /**
     * @var \Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role", referencedColumnName="id")
     * })
     */
    private $role;



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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->username = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Set tokenics
     *
     * @param string $tokenics
     * @return User
     */
    public function setTokenics($tokenics)
    {
        $this->tokenics = $tokenics;

        return $this;
    }

    /**
     * Get tokenics
     *
     * @return string 
     */
    public function getTokenics()
    {
        return $this->tokenics;
    }


    /**
     * Set role
     *
     * @param \Site\TrailBundle\Entity\Role $role
     * @return User
     */
    public function setRoles(\Site\TrailBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    
    public function getRoles()
    {
        return $this->role->getRole();
    }
    
    public function getRoleId()
    {
        return $this->role->getId();
    }

    public function eraseCredentials() {
        
    }

    public function getSalt() {
        return $this->salt;
    }
    
    public function setSalt($salt) {
        $this->salt = $salt;
    }


    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'email'=> $this->getEmail(),
            'username'=> $this->getUsername(),
            'role'=> $this->getRoles()[0],
        );
    }

}



