<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Membre
 *
 * @ORM\Table(name="membre", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})}, indexes={@ORM\Index(name="fk_membre_role_idx", columns={"role"}), @ORM\Index(name="fk_membre_avatar_idx", columns={"avatar"})})
 * @ORM\Entity
 */
class Membre
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
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datenaissance", type="date", nullable=false)
     */
    private $datenaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=45, nullable=false)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="licence", type="string", length=255, nullable=false)
     */
    private $licence;

    /**
     * @var string
     *
     * @ORM\Column(name="tokenics", type="string", length=255, nullable=false)
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
     * @var \Image
     *
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="avatar", referencedColumnName="id")
     * })
     */
    private $avatar;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Itineraire", inversedBy="membres")
     * @ORM\JoinTable(name="favoris",
     *   joinColumns={
     *     @ORM\JoinColumn(name="membres", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="itinerairefavoris", referencedColumnName="id")
     *   }
     * )
     */
    private $itinerairefavoris;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Itineraire", inversedBy="membre")
     * @ORM\JoinTable(name="note",
     *   joinColumns={
     *     @ORM\JoinColumn(name="membre", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="itineraire", referencedColumnName="id")
     *   }
     * )
     */
    private $itineraire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itinerairefavoris = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itineraire = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set email
     *
     * @param string $email
     * @return Membre
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * Set nom
     *
     * @param string $nom
     * @return Membre
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Membre
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set datenaissance
     *
     * @param \DateTime $datenaissance
     * @return Membre
     */
    public function setDatenaissance($datenaissance)
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    /**
     * Get datenaissance
     *
     * @return \DateTime 
     */
    public function getDatenaissance()
    {
        return $this->datenaissance;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Membre
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set licence
     *
     * @param string $licence
     * @return Membre
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * Get licence
     *
     * @return string 
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * Set tokenics
     *
     * @param string $tokenics
     * @return Membre
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
     * @return Membre
     */
    public function setRole(\Site\TrailBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Site\TrailBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set avatar
     *
     * @param \Site\TrailBundle\Entity\Image $avatar
     * @return Membre
     */
    public function setAvatar(\Site\TrailBundle\Entity\Image $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Site\TrailBundle\Entity\Image 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add itinerairefavoris
     *
     * @param \Site\TrailBundle\Entity\Itineraire $itinerairefavoris
     * @return Membre
     */
    public function addItinerairefavori(\Site\TrailBundle\Entity\Itineraire $itinerairefavoris)
    {
        $this->itinerairefavoris[] = $itinerairefavoris;

        return $this;
    }

    /**
     * Remove itinerairefavoris
     *
     * @param \Site\TrailBundle\Entity\Itineraire $itinerairefavoris
     */
    public function removeItinerairefavori(\Site\TrailBundle\Entity\Itineraire $itinerairefavoris)
    {
        $this->itinerairefavoris->removeElement($itinerairefavoris);
    }

    /**
     * Get itinerairefavoris
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItinerairefavoris()
    {
        return $this->itinerairefavoris;
    }

    /**
     * Add itineraire
     *
     * @param \Site\TrailBundle\Entity\Itineraire $itineraire
     * @return Membre
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
}
