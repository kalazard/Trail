<?php

namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Site\TrailBundle\Entity\Evenement;
use Site\TrailBundle\Entity\User;
use Site\TrailBundle\Entity\Entrainement;
use Site\TrailBundle\Entity\EntrainementPersonnel;
use Site\TrailBundle\Entity\EvenementDivers;
use Site\TrailBundle\Entity\SortieDecouverte;
use Site\TrailBundle\Entity\Programme;
use Site\TrailBundle\Entity\LieuRendezVous;
use Site\TrailBundle\Entity\Participants;

use Symfony\Component\HttpFoundation\Response;


class CalendrierController extends Controller
{
    /*Retourne les évènements qui ont été créés par un utilisateur ainsi
     *que les évènements auxquels il participle
     */
    public static function getAllEventFrom($idUser, $em, $dateDebut='', $dateFin='')
    {
        //Si on souhaite selectionner les évènements dans une certaine période (pour les ICS)
        $bonusWhere = '';
        
        if($dateDebut != '')
        {
            $bonusWhere = "AND e.dateDebut >= '" . date_format($dateDebut, 'Y-m-d H:i:s') . "' ";
        }
        
        if($dateFin != '')
        {
            $bonusWhere .= "AND e.dateDebut <= '" . date_format($dateFin, 'Y-m-d H:i:s') . "' ";
        }
        
        //$em = $this->getDoctrine()->getManager();
        
        //Sélection des évènement où on participe
        $req = "SELECT e.id ";
        $req .= "FROM SiteTrailBundle:Participants sd, SiteTrailBundle:Evenement e ";
        $req .= "WHERE sd.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND sd.user = ".$idUser;        
        $query = $em->createQuery($req);
        $listeIdEvenementParticipation = $query->getResult();
        
        //On récupère un tableau de tableau dans le résultat de la requête
        $idEvenementParticipation = '';
        //Boucle pour modifier le type du résultat en string
        foreach($listeIdEvenementParticipation as $idParticipation)
        {
            $idEvenementParticipation .= $idParticipation['id'] . ',';
        }
        $idEvenementParticipation = substr($idEvenementParticipation, 0, strlen($idEvenementParticipation)-1);
        
        //Si participe à des évènements pendant le laps de temps donné alors
        //on les récupère
        
        if($idEvenementParticipation)
        {
            //Récupération des "catégories" des évènements où on participe
            //Selection des entrainement auxquels on participe
            $req = "SELECT en ";
            $req .= "FROM SiteTrailBundle:Entrainement en, SiteTrailBundle:Evenement e ";
            $req .= "WHERE en.evenement = e.id ";
            $req .= $bonusWhere;
            $req .= "AND e.id IN (" . $idEvenementParticipation . ")";
            $query = $em->createQuery($req);
            $listeEvenementParticipation[] = $query->getResult();

            //Selection des entrainementPersonnel auxquels on participe
            $req = "SELECT ep ";
            $req .= "FROM SiteTrailBundle:EntrainementPersonnel ep, SiteTrailBundle:Evenement e ";
            $req .= "WHERE ep.evenement = e.id ";
            $req .= $bonusWhere;
            $req .= "AND e.id IN (" . $idEvenementParticipation . ")";
            $query = $em->createQuery($req);
            $listeEvenementParticipation[] = $query->getResult();

            //Selection des evenementDivers auxquels on participe
            $req = "SELECT ed ";
            $req .= "FROM SiteTrailBundle:EvenementDivers ed, SiteTrailBundle:Evenement e ";
            $req .= "WHERE ed.evenement = e.id ";
            $req .= $bonusWhere;
            $req .= "AND e.id IN (" . $idEvenementParticipation . ")";
            $query = $em->createQuery($req);
            $listeEvenementParticipation[] = $query->getResult();


            //Selection des sortieDecouverte auxquels on participe
            $req = "SELECT sd ";
            $req .= "FROM SiteTrailBundle:SortieDecouverte sd, SiteTrailBundle:Evenement e ";
            $req .= "WHERE sd.evenement = e.id ";
            $req .= $bonusWhere;
            $req .= "AND e.id IN (" . $idEvenementParticipation . ")";
            $query = $em->createQuery($req);
            $listeEvenementParticipation[] = $query->getResult();  
        }
        else
        {
            $listeEvenementParticipation[] = array();
            $listeEvenementParticipation[] = array();
            $listeEvenementParticipation[] = array();
            $listeEvenementParticipation[] = array();
        }
        
        //Selection des évènement où on est le créateur
        //Selection des entrainement
        $req = "SELECT en ";
        $req .= "FROM SiteTrailBundle:Entrainement en, SiteTrailBundle:Evenement e ";
        $req .= "WHERE en.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND e.createur = " . $idUser;
        $query = $em->createQuery($req);
        $listeEvenement[] = array_merge($query->getResult(), $listeEvenementParticipation[0]);
        
        //Selection des entrainementPersonnel
        $req = "SELECT ep ";
        $req .= "FROM SiteTrailBundle:EntrainementPersonnel ep, SiteTrailBundle:Evenement e ";
        $req .= "WHERE ep.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND e.createur = " . $idUser;
        $query = $em->createQuery($req);
        $listeEvenement[] = array_merge($query->getResult(), $listeEvenementParticipation[1]);
        
        //Selection des evenementDivers
        $req = "SELECT ed ";
        $req .= "FROM SiteTrailBundle:EvenementDivers ed, SiteTrailBundle:Evenement e ";
        $req .= "WHERE ed.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND e.createur = " . $idUser;
        $query = $em->createQuery($req);
        $listeEvenement[] = array_merge($query->getResult(), $listeEvenementParticipation[2]);
        
        //Selection des sortieDecouverte
        $req = "SELECT sd ";
        $req .= "FROM SiteTrailBundle:SortieDecouverte sd, SiteTrailBundle:Evenement e ";
        $req .= "WHERE sd.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND e.createur = " . $idUser;
        $query = $em->createQuery($req);
        $listeEvenement[] = array_merge($query->getResult(), $listeEvenementParticipation[3]);
        
        return $listeEvenement;
    }
    
    public function indexAction()
    {
        if($this->getUser())
        {
            $idUser = $this->getUser()->getId();
        }
        else
        {
            $idUser = 0;
        }
        
        $listeEvenement = CalendrierController::getAllEventFrom($idUser, $this->getDoctrine()->getManager());

        $content = $this->get("templating")->render("SiteTrailBundle:Calendrier:calendrier.html.twig", array(
                                                    'listeEvenement' => $listeEvenement));
        
        return new Response($content);
    }
    
    public function afficherFormAction(Request $request)
    {
        if(isset($_REQUEST['dateCliquee']))
        {
            $dateCliquee = htmlspecialchars($_REQUEST['dateCliquee']);
        }
        else
        {
            $dateCliquee = '';
        }
        
        if($this->getUser())
        {
            $idUser = $this->getUser()->getId();
        }
        else
        {
            $idUser = 0;
        }
        
        $event = new Evenement;
        $formBuilder = $this->get('form.factory')->createBuilder('form', $event);
        $formBuilder
                ->setAction($this->generateUrl('calendrierForm'))
                ->add('titre', 'text')
                ->add('description', 'text')
                ->add('lienKid', 'url')
                ->add('alias', 'text')
                ->add('status', 'text')
                ->add('date_debut', 'datetime', array(
                                    'data' => new \DateTime($dateCliquee)))
                ->add('date_fin', 'datetime', array(
                                    'data' => new \DateTime($dateCliquee)));
        $form = $formBuilder->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) 
        {
            $type = htmlspecialchars($_POST['type']);
            $idCreateur = htmlspecialchars($_POST['createur']);
            
            $manager=$this->getDoctrine()->getManager();
            $repository=$manager->getRepository("SiteTrailBundle:User");
            $event->setCreateur($repository->findOneById($idCreateur));
            $event->setDateCreation(new \DateTime("now"));
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();
            
            switch ($type)
            {
                case '1': //Entrainement
                    $repository=$manager->getRepository("SiteTrailBundle:Programme");
                    $idProgramme = htmlspecialchars($_POST['programme']);
                    $programme = $repository->findOneById($idProgramme);
                    $repository=$manager->getRepository("SiteTrailBundle:LieuRendezVous");
                    $idLieu = htmlspecialchars($_POST['lieu']);
                    $lieu = $repository->findOneById($idLieu);
                    $entrainement = new Entrainement;
                    $entrainement->setProgramme($programme);
                    $entrainement->setLieuRendezVous($lieu);
                    $entrainement->setEvenement($event);
                    $manager->persist($entrainement);
                    $manager->flush();
                    break;
                case '2': //Entrainement personnel
                    $entrainementPerso = new EntrainementPersonnel;
                    $entrainementPerso->setEvenement($event);
                    $manager->persist($entrainementPerso);
                    $manager->flush();
                    break;
                case '3': //Evenement divers
                    $label = htmlspecialchars($_POST['label']);
                    $evenementDivers = new EvenementDivers;
                    $evenementDivers->setLabel($label);
                    $evenementDivers->setEvenement($event);
                    $manager->persist($evenementDivers);
                    $manager->flush();
                    break;
                case '4': //Sortie découverte
                    $repository=$manager->getRepository("SiteTrailBundle:LieuRendezVous");
                    $idLieu = htmlspecialchars($_POST['lieu']);
                    $lieu = $repository->findOneById($idLieu);
                    $sortieDecouverte = new SortieDecouverte;
                    $sortieDecouverte->setLieuRendezVous($lieu);
                    $sortieDecouverte->setEvenement($event);
                    $manager->persist($sortieDecouverte);
                    $manager->flush();
                    break;
                default:
                    break;
            }
            
            if(isset($_REQUEST['participants']))
            {
                foreach($_REQUEST['participants'] as $monParticipant)
                {
                    $repository=$manager->getRepository("SiteTrailBundle:User");
                    $idParticipant = htmlspecialchars($monParticipant);
                    $userParticip = $repository->findOneById($idParticipant);
                    $participant = new Participants;
                    $participant->setUser($userParticip);
                    $participant->setEvenement($event);
                    $manager->persist($participant);
                    $manager->flush();
                }
            }

            $request->getSession()->getFlashBag()->add('notice', 'Evènement ajouté');

            return $this->redirect($this->generateUrl('calendrier'));
        }
        
        $manager=$this->getDoctrine()->getManager();
        $repository=$manager->getRepository("SiteTrailBundle:Programme");        
        $listeProgramme = $repository->findAll();
        $repository=$manager->getRepository("SiteTrailBundle:LieuRendezVous");        
        $listeLieuRendezVous = $repository->findAll();
       
        $query = $manager->createQuery(
            'SELECT u
            FROM SiteTrailBundle:User u
            WHERE u.id != :createur'
        )->setParameter('createur', $idUser);
        $listeUser = $query->getResult();        
        
        $formulaire = $this->get("templating")->render("SiteTrailBundle:Calendrier:ajouterEventForm.html.twig", array(
                                                            'listeProgramme' => $listeProgramme,
                                                            'listeLieuRendezVous' => $listeLieuRendezVous,
                                                            'listeUser' => $listeUser,
                                                            'form' => $form->createView()
                                                        ));
        
        return new Response($formulaire);
    }
    
    public function afficherDetailEvenementAction()
    {
        $idClasse = htmlspecialchars($_REQUEST['idClasse']);
        $idObj = htmlspecialchars($_REQUEST['idObj']);
        
        switch ($idClasse)
        {
            case '1': //Entrainement
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:Entrainement");        
                $evenement = $repository->findOneById($idObj);
                break;
            case '2': //Entrainement personnel
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:EntrainementPersonnel");        
                $evenement = $repository->findOneById($idObj);
                break;
            case '3': //Evenement divers
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:EvenementDivers");        
                $evenement = $repository->findOneById($idObj);
                break;
            case '4': //Sortie découverte
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:SortieDecouverte");        
                $evenement = $repository->findOneById($idObj);
                break;
            default:
                break;
        }
        
        $resp = $this->get("templating")->render("SiteTrailBundle:Calendrier:detailEvenement.html.twig", array(
                                                            'evenement' => $evenement,
                                                            'idClasse' => $idClasse
                                                        ));
        
        return new Response($resp);
    }
}