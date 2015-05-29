<?php

namespace Site\TrailBundle\Services;
use Site\TrailBundle\Entity\Parcours;

class EvenementService
{
    protected $entityManager;
    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->entityManager = $em;
    }
    
    public function getEventAndEventUsed($idIti)
    {
        $listeEvenements = array();
        $listeEvenementsUtilises = array();
        $arrayEventId = array();
        $listeParcours = $this->entityManager->getRepository("SiteTrailBundle:Parcours")->findBy(array('idItineraire' => $idIti));
        
        foreach($listeParcours as $parcours)
        {
            $arrayEventId[] = $parcours->getEvenement()->getId();
        }
        
        if(sizeof($arrayEventId) == 0)
        {
            $arrayEventId[] = 0;
        }
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Entrainement eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        //$query .= "AND event.id NOT IN (".implode(',', $arrayEventId).")";
        $listeEvenements[] = $this->entityManager->createQuery($query)->getResult();
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Entrainementpersonnel eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        //$query .= "AND event.id NOT IN (".implode(',', $arrayEventId).")";
        $listeEvenements[] = $this->entityManager->createQuery($query)->getResult();
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Evenementdivers eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        //$query .= "AND event.id NOT IN (".implode(',', $arrayEventId).")";
        $listeEvenements[] = $this->entityManager->createQuery($query)->getResult();
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Sortiedecouverte eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        //$query .= "AND event.id NOT IN (".implode(',', $arrayEventId).")";
        $listeEvenements[] = $this->entityManager->createQuery($query)->getResult();
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Courseofficielle eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        //$query .= "AND event.id NOT IN (".implode(',', $arrayEventId).")";
        $listeEvenements[] = $this->entityManager->createQuery($query)->getResult();
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Entrainement eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        $query .= "AND event.id IN (".implode(',', $arrayEventId).")";
        $listeEvenementsUtilises = $this->entityManager->createQuery($query)->getResult();
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Entrainementpersonnel eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        $query .= "AND event.id IN (".implode(',', $arrayEventId).")";
        $listeEvenementsUtilises = array_merge($listeEvenementsUtilises, $this->entityManager->createQuery($query)->getResult());
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Evenementdivers eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        $query .= "AND event.id IN (".implode(',', $arrayEventId).")";
        $listeEvenementsUtilises = array_merge($listeEvenementsUtilises, $this->entityManager->createQuery($query)->getResult());
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Sortiedecouverte eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        $query .= "AND event.id IN (".implode(',', $arrayEventId).")";
        $listeEvenementsUtilises = array_merge($listeEvenementsUtilises, $this->entityManager->createQuery($query)->getResult());
        
        $query = "SELECT eventCat ";
        $query .= "FROM SiteTrailBundle:Courseofficielle eventCat, SiteTrailBundle:Evenement event ";
        $query .= "WHERE eventCat.evenement = event.id ";
        $query .= "AND event.id IN (".implode(',', $arrayEventId).")";
        $listeEvenementsUtilises = array_merge($listeEvenementsUtilises, $this->entityManager->createQuery($query)->getResult());

        $res = array('allEvent' => $listeEvenements,
                        'usedEvent' => $listeEvenementsUtilises);
        
        return $res;
    }
    
    public function updateParcours($arrayIdEvenement, $idItineraire)
    {        
        $listeParcours = $this->entityManager->getRepository("SiteTrailBundle:Parcours")->findBy(array('idItineraire' => $idItineraire));
 
        if(is_array($listeParcours))
        {
            foreach($listeParcours as $eventIti)
            {
                $this->entityManager->remove($eventIti);
                $this->entityManager->flush();
            }
        }

        if(is_array($arrayIdEvenement))
        {
            foreach($arrayIdEvenement as $id)
            {
                $parcours = new Parcours();
                
                $evenement = $this->entityManager->getRepository("SiteTrailBundle:Evenement")->findOneById($id);                
                $parcours->setEvenement($evenement);
                $parcours->setIdItineraire($idItineraire);
                $this->entityManager->persist($parcours);
                $this->entityManager->flush();
            }
        }
    }
    
    public function getUsedIti($idEvent)
    {
        $evenement = $this->entityManager->getRepository("SiteTrailBundle:Evenement")->findOneBy(array('id' => $idEvent));
        $listeParcours = $this->entityManager->getRepository("SiteTrailBundle:Parcours")->findBy(array('evenement' => $evenement));
        $listeIdIti = array();
        
        foreach($listeParcours as $parcours)
        {
            $listeIdIti[] = $parcours->getIdItineraire();
        }
        
        return $listeIdIti;
    }
}