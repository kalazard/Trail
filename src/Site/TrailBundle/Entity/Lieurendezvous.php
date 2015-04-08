<?php

namespace Site\TrailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Lieurendezvous
 *
 * @ORM\Table(name="lieurendezvous")
 * @ORM\Entity
 */
class Lieurendezvous implements JsonSerializable
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="coordonnees", type="integer", nullable=false)
     */
    private $coordonnees;



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
     * Set titre
     *
     * @param string $titre
     * @return Lieurendezvous
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Lieurendezvous
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set coordonnees
     *
     * @param integer $coordonnees
     * @return Lieurendezvous
     */
    public function setCoordonnees($coordonnees)
    {
        $this->coordonnees = $coordonnees;

        return $this;
    }

    /**
     * Get coordonnees
     *
     * @return integer 
     */
    public function getCoordonnees()
    {
        return $this->coordonnees;
    }
    
    public function jsonSerialize() {
        return [
            'id' => $this->getId(),
            'titre' => $this->getTitre(),
            'description' => $this->getDescription(),
            'coordonnees' => $this->getCoordonnes() 
        ];
    }
}