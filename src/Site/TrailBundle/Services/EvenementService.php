<?php

namespace Site\TrailBundle\Services;
use Site\TrailBundle\Entity\Evenementitineraire;

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
        $listeEvenementItineraire = $this->entityManager->getRepository("SiteTrailBundle:Evenementitineraire")->findBy(array('idItineraire' => $idIti));
        
        foreach($listeEvenementItineraire as $eventIti)
        {
            $arrayEventId[] = $eventIti->getEvenement()->getId();
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
    
    public function updateEvenementItineraire($arrayIdEvenement, $idItineraire, $nomItineraire)
    {        
        $listeEvenementItineraire = $this->entityManager->getRepository("SiteTrailBundle:Evenementitineraire")->findBy(array('idItineraire' => $idItineraire));
 
        if(is_array($listeEvenementItineraire))
        {
            foreach($listeEvenementItineraire as $eventIti)
            {
                $this->entityManager->remove($eventIti);
                $this->entityManager->flush();
            }
        }

        if(is_array($arrayIdEvenement))
        {
            foreach($arrayIdEvenement as $id)
            {
                $evenementItineraire = new EvenementItineraire();
                
                $evenement = $this->entityManager->getRepository("SiteTrailBundle:Evenement")->findOneById($id);                
                $evenementItineraire->setEvenement($evenement);
                $evenementItineraire->setIdItineraire($idItineraire);
                $evenementItineraire->setNomItineraire($nomItineraire);
                $this->entityManager->persist($evenementItineraire);
                $this->entityManager->flush();
            }
        }
    }
}