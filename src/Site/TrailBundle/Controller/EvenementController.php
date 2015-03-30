<?php namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Site\TrailBundle\Entity\Evenement;
use Site\TrailBundle\Entity\Membre;
use Site\TrailBundle\Entity\Entrainement;
use Site\TrailBundle\Entity\Entrainementpersonnel;
use Site\TrailBundle\Entity\Evenementdivers;
use Site\TrailBundle\Entity\Sortiedecouverte;
use Site\TrailBundle\Entity\Programme;
use Site\TrailBundle\Entity\Lieurendezvous;
use Site\TrailBundle\Entity\Participants;
use Site\TrailBundle\Entity\Courseofficielle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Response;


class EvenementController extends Controller
{
    //Retourne l'événement l'objet evenement et l'objet de sa categorie
    public static function getEvenementEtEvenementDeCategorie($manager, $idClasse, $idEvenementDeClasse)
    {
        //On récupère l'événement de la catégorie
        switch ($idClasse)
        {
            case '1': //Entrainement
                $repository=$manager->getRepository("SiteTrailBundle:Entrainement");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            case '2': //Entrainement personnel
                $repository=$manager->getRepository("SiteTrailBundle:Entrainementpersonnel");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            case '3': //Evenement divers
                $repository=$manager->getRepository("SiteTrailBundle:Evenementdivers");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            case '4': //Sortie découverte
                $repository=$manager->getRepository("SiteTrailBundle:Sortiedecouverte");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            case '5': //Course officielle
                $repository=$manager->getRepository("SiteTrailBundle:Courseofficielle");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            default:
                break;
        }
        
        //On récupère l'objet événement associé
        $repository=$manager->getRepository("SiteTrailBundle:Evenement"); 
        $monEvenementAssocie = $repository->findOneById($monEvenementDeClasse->getEvenement()->getId());
        
        return array($monEvenementDeClasse, $monEvenementAssocie);
    }
    
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
        $req .= "AND sd.membre = ".$idUser;        
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
            $req .= "FROM SiteTrailBundle:Entrainementpersonnel ep, SiteTrailBundle:Evenement e ";
            $req .= "WHERE ep.evenement = e.id ";
            $req .= $bonusWhere;
            $req .= "AND e.id IN (" . $idEvenementParticipation . ")";
            $query = $em->createQuery($req);
            $listeEvenementParticipation[] = $query->getResult();

            //Selection des evenementDivers auxquels on participe
            $req = "SELECT ed ";
            $req .= "FROM SiteTrailBundle:Evenementdivers ed, SiteTrailBundle:Evenement e ";
            $req .= "WHERE ed.evenement = e.id ";
            $req .= $bonusWhere;
            $req .= "AND e.id IN (" . $idEvenementParticipation . ")";
            $query = $em->createQuery($req);
            $listeEvenementParticipation[] = $query->getResult();


            //Selection des sortieDecouverte auxquels on participe
            $req = "SELECT sd ";
            $req .= "FROM SiteTrailBundle:Sortiedecouverte sd, SiteTrailBundle:Evenement e ";
            $req .= "WHERE sd.evenement = e.id ";
            $req .= $bonusWhere;
            $req .= "AND e.id IN (" . $idEvenementParticipation . ")";
            $query = $em->createQuery($req);
            $listeEvenementParticipation[] = $query->getResult();
            
            //Selection des courseOfficielle auxquelles on participe
            $req = "SELECT co ";
            $req .= "FROM SiteTrailBundle:Courseofficielle co, SiteTrailBundle:Evenement e ";
            $req .= "WHERE co.evenement = e.id ";
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
        $req .= "FROM SiteTrailBundle:Entrainementpersonnel ep, SiteTrailBundle:Evenement e ";
        $req .= "WHERE ep.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND e.createur = " . $idUser;
        $query = $em->createQuery($req);
        $listeEvenement[] = array_merge($query->getResult(), $listeEvenementParticipation[1]);
        
        //Selection des evenementDivers
        $req = "SELECT ed ";
        $req .= "FROM SiteTrailBundle:Evenementdivers ed, SiteTrailBundle:Evenement e ";
        $req .= "WHERE ed.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND e.createur = " . $idUser;
        $query = $em->createQuery($req);
        $listeEvenement[] = array_merge($query->getResult(), $listeEvenementParticipation[2]);
        
        //Selection des sortieDecouverte
        $req = "SELECT sd ";
        $req .= "FROM SiteTrailBundle:Sortiedecouverte sd, SiteTrailBundle:Evenement e ";
        $req .= "WHERE sd.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND e.createur = " . $idUser;
        $query = $em->createQuery($req);
        $listeEvenement[] = array_merge($query->getResult(), $listeEvenementParticipation[3]);
        
        //Selection des courseOfficielle
        $req = "SELECT co ";
        $req .= "FROM SiteTrailBundle:Courseofficielle co, SiteTrailBundle:Evenement e ";
        $req .= "WHERE co.evenement = e.id ";
        $req .= $bonusWhere;
        $req .= "AND e.createur = " . $idUser;
        $query = $em->createQuery($req);
        $listeEvenement[] = array_merge($query->getResult(), $listeEvenementParticipation[4]);
        
        return $listeEvenement;
    }
    
    public function indexAction()
    {
        if($this->getUser())
        {
            $idUser = $this->getUser()->getId();
            $listeEvenement = EvenementController::getAllEventFrom($idUser, $this->getDoctrine()->getManager());
            $content = $this->get("templating")->render("SiteTrailBundle:Event:calendrier.html.twig", array(
                                                        'listeEvenement' => $listeEvenement));

            return new Response($content);
        }
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        } 
    }
    
    public function afficherFormAction(Request $request)
    {
        $dateCliquee = $request->request->get('dateCliquee', '');
        /*if(isset($_REQUEST['dateCliquee']))
        {
            $dateCliquee = htmlspecialchars($_REQUEST['dateCliquee']);
        }
        else
        {
            $dateCliquee = '';
        }*/
        
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
                ->setAction($this->generateUrl('site_trail_evenement_calendrierForm'))
                ->add('titre', 'text', array('max_length' => 255))
                ->add('description', 'text', array('max_length' => 255))
                ->add('lienKid', 'url', array('required' => false, 'max_length' => 255))
                ->add('status', 'text', array('max_length' => 255))
                ->add('date_debut', 'datetime', array(
                                    'data' => new \DateTime($dateCliquee)))
                ->add('date_fin', 'datetime', array(
                                    'data' => new \DateTime($dateCliquee)));
        $form = $formBuilder->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) 
        {
            //$type = htmlspecialchars($_POST['type']);
            $type = $request->request->get('type', '');
            //$idCreateur = htmlspecialchars($_POST['createur']);
            $idCreateur = $request->request->get('createur', '');
            
            $manager=$this->getDoctrine()->getManager();
            $repository=$manager->getRepository("SiteTrailBundle:Membre");
            $event->setCreateur($repository->findOneById($idCreateur));
            $event->setDateCreation(new \DateTime("now"));
            $event->setAlias("alias");
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();
            
            switch ($type)
            {
                case '1': //Entrainement
                    $repository=$manager->getRepository("SiteTrailBundle:Programme");
                    //$idProgramme = htmlspecialchars($_POST['programme']);
                    $idProgramme = $request->request->get('programme', '');
                    $programme = $repository->findOneById($idProgramme);
                    $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");
                    //$idLieu = htmlspecialchars($_POST['lieu']);
                    $idLieu = $request->request->get('lieu', '');
                    $lieu = $repository->findOneById($idLieu);
                    $entrainement = new Entrainement;
                    $entrainement->setProgramme($programme);
                    $entrainement->setLieuRendezVous($lieu);
                    $entrainement->setEvenement($event);
                    $manager->persist($entrainement);
                    $manager->flush();
                    break;
                case '2': //Entrainement personnel
                    $entrainementPerso = new Entrainementpersonnel;
                    $entrainementPerso->setEvenement($event);
                    $manager->persist($entrainementPerso);
                    $manager->flush();
                    break;
                case '3': //Evenement divers
                    //$label = htmlspecialchars($_POST['label']);
                    $description = $request->request->get('desc', '');
                    $evenementDivers = new Evenementdivers;
                    $evenementDivers->setDescription($description);
                    $evenementDivers->setEvenement($event);
                    $manager->persist($evenementDivers);
                    $manager->flush();
                    break;
                case '4': //Sortie découverte
                    $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");
                    //$idLieu = htmlspecialchars($_POST['lieu']);
                    $idLieu = $request->request->get('lieu', '');
                    $lieu = $repository->findOneById($idLieu);
                    $sortieDecouverte = new Sortiedecouverte;
                    $sortieDecouverte->setLieuRendezVous($lieu);
                    $sortieDecouverte->setEvenement($event);
                    $manager->persist($sortieDecouverte);
                    $manager->flush();
                    break;
                case '5': //Course officielle
                    $repository=$manager->getRepository("SiteTrailBundle:Courseofficielle");
                    $siteUrl = $request->request->get('siteUrl', '');
                    $courseOfficielle = new Courseofficielle();
                    $courseOfficielle->setSiteUrl($siteUrl);
                    $courseOfficielle->setEvenement($event);
                    $manager->persist($courseOfficielle);
                    $manager->flush();
                default:
                    break;
            }
            
            //if(isset($_REQUEST['participants']))
            if($request->request->get('participants', '') != '')
            {
                foreach($request->request->get('participants', '') as $monParticipant)
                {
                    $repository=$manager->getRepository("SiteTrailBundle:Membre");
                    $idParticipant = htmlspecialchars($monParticipant);
                    $userParticip = $repository->findOneById($idParticipant);
                    $participant = new Participants;
                    $participant->setMembre($userParticip);
                    $participant->setEvenement($event);
                    $manager->persist($participant);
                    $manager->flush();
                }
            }

            $request->getSession()->getFlashBag()->add('notice', 'Evènement ajouté');

            return $this->redirect($this->generateUrl('site_trail_evenement'));
        }
        
        $manager=$this->getDoctrine()->getManager();
        $repository=$manager->getRepository("SiteTrailBundle:Programme");        
        $listeProgramme = $repository->findAll();
        $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");        
        $listeLieuRendezVous = $repository->findAll();
       
        $query = $manager->createQuery(
            'SELECT u
            FROM SiteTrailBundle:Membre u
            WHERE u.id != :createur'
        )->setParameter('createur', $idUser);
        $listeUser = $query->getResult();        
        
        $formulaire = $this->get("templating")->render("SiteTrailBundle:Event:ajouterEventForm.html.twig", array(
                                                            'listeProgramme' => $listeProgramme,
                                                            'listeLieuRendezVous' => $listeLieuRendezVous,
                                                            'listeUser' => $listeUser,
                                                            'form' => $form->createView()
                                                        ));
        
        return new Response($formulaire);
    }
    
    public function afficherDetailEvenementAction(Request $request)
    {
        //$idClasse = htmlspecialchars($_REQUEST['idClasse']);
        $idClasse = $request->request->get('idClasse', '');
        //$idObj = htmlspecialchars($_REQUEST['idObj']);
        $idObj = $request->request->get('idObj', '');
        
        switch ($idClasse)
        {
            case '1': //Entrainement
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:Entrainement");        
                $evenement = $repository->findOneById($idObj);
                break;
            case '2': //Entrainement personnel
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:Entrainementpersonnel");        
                $evenement = $repository->findOneById($idObj);
                break;
            case '3': //Evenement divers
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:Evenementdivers");        
                $evenement = $repository->findOneById($idObj);
                break;
            case '4': //Sortie découverte
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:Sortiedecouverte");        
                $evenement = $repository->findOneById($idObj);
                break;
            case '5': //Course officielle
                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:Courseofficielle");        
                $evenement = $repository->findOneById($idObj);
                break;
            default:
                break;
        }
        
        
        $resp = $this->get("templating")->render("SiteTrailBundle:Event:detailEvenement.html.twig", array(
                                                            'evenement' => $evenement,
                                                            'idClasse' => $idClasse
                                                        ));
        
        return new Response($resp);
    }
    
    public function supprEvenementAction(Request $request)
    {        
        $idClasse = $request->request->get('idClasse', '');
        $idEvenementDeClasse = $request->request->get('idObj', '');
        
        /*$idClasse = '2';
        $idEvenementDeClasse = '3';*/
        
        $manager=$this->getDoctrine()->getManager();
        
        //On récupère l'événement de la catégorie
        switch ($idClasse)
        {
            case '1': //Entrainement
                $repository=$manager->getRepository("SiteTrailBundle:Entrainement");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            case '2': //Entrainement personnel
                $repository=$manager->getRepository("SiteTrailBundle:Entrainementpersonnel");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            case '3': //Evenement divers
                $repository=$manager->getRepository("SiteTrailBundle:Evenementdivers");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            case '4': //Sortie découverte
                $repository=$manager->getRepository("SiteTrailBundle:Sortiedecouverte");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            case '5': //Course officielle
                $repository=$manager->getRepository("SiteTrailBundle:Courseofficielle");        
                $monEvenementDeClasse = $repository->findOneById($idEvenementDeClasse);
                break;
            default:
                break;
        }
        
        //On récupère l'objet événement associé
        $repository=$manager->getRepository("SiteTrailBundle:Evenement"); 
        $monEvenementAssocie = $repository->findOneById($monEvenementDeClasse->getEvenement()->getId());
        
        //On récupère les participants à cet événement
        $repository=$manager->getRepository("SiteTrailBundle:Participants"); 
        $participants = $repository->findBy(
            array('evenement' => $monEvenementAssocie->getId())
        );

        $em = $this->getDoctrine()->getEntityManager();
        
        foreach($participants as $participant)
        {
           //On récupère l'entité participation associée à ce participant
            $repository=$manager->getRepository("SiteTrailBundle:Participation"); 
            $participation = $repository->findOneById($monEvenementAssocie->getId());
            
            //Suppression de l'entité participation et du participant
            if($participation != null)
            {
               $em->remove($participation); 
            }
            
            $em->remove($participant);       
        }
        
        //Suppression de l'entité de l'événement de la classe
        $em->remove($monEvenementDeClasse);
        
        //suppression de l'entité evenement assicié à cet evenement précis
        $em->remove($monEvenementAssocie);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('notice', 'Evènement supprimé');

        return $this->redirect($this->generateUrl('site_trail_evenement'));
    }
    
    public function modifEvenementAction(Request $request)
    {
        $idEvenementDeCategorie = 0;        
        $idUser = $this->getUser()->getId();
        $idClasse = $request->request->get('idClasse', '');
        $idEvenementDeClasse = $request->request->get('idObj', '');
        
        $tabEvenements = EvenementController::getEvenementEtEvenementDeCategorie($this->getDoctrine()->getManager(), $idClasse, $idEvenementDeClasse);
        
        $evenementDeCategorie = $tabEvenements[0];
        $evenementAssocie = $tabEvenements[1];
        
        $event = new Evenement;
        $formBuilder = $this->get('form.factory')->createBuilder('form', $event);
        $formBuilder
                ->setAction($this->generateUrl('site_trail_evenement_modif'))
                ->add('titre', 'text', array('max_length' => 255,
                                                'data' => $evenementAssocie->getTitre()))
                ->add('description', 'text', array('max_length' => 255,
                                                    'data' => $evenementAssocie->getDescription()))
                ->add('lienKid', 'url', array('required' => false,
                                                'max_length' => 255,
                                                'data' => $evenementAssocie->getLienKid()))
                ->add('status', 'text', array('max_length' => 255,
                                                'data' => $evenementAssocie->getStatus()))
                ->add('date_debut', 'datetime', array(
                                    'data' => $evenementAssocie->getDateDebut()))
                ->add('date_fin', 'datetime', array(
                                    'data' => $evenementAssocie->getDateFin()));
        
        switch ($idClasse)
        {
            case '1': //Entrainement
                $formBuilder->add('programme', 'text', array('mapped' => false));
                $formBuilder->add('lieuRendezVous', 'text', array('mapped' => false));
                break;
            case '2': //Entrainement personnel
                break;
            case '3': //Evenement divers
                break;
            case '4': //Sortie découverte
                break;
            case '5': //Course officielle
                break;
            default:
                break;
        }
        
        
       
        /*$form = $this->createFormBuilder()->add('titre', 'text', array('mapped' => false))
                ->getForm();*/
        
        
        
        $form = $formBuilder->getForm();
                
        
        $form->handleRequest($request);
        
        if ($form->isValid()) 
        {
            $type = $request->request->get('type', '');
            $idCreateur = $request->request->get('createur', '');
            
            $manager=$this->getDoctrine()->getManager();
            $repository=$manager->getRepository("SiteTrailBundle:Membre");
            $event->setCreateur($repository->findOneById($idCreateur));
            $event->setDateCreation(new \DateTime("now"));
            $event->setAlias("alias");
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();
            
            switch ($type)
            {
                case '1': //Entrainement
                    $repository=$manager->getRepository("SiteTrailBundle:Programme");
                    //$idProgramme = htmlspecialchars($_POST['programme']);
                    $idProgramme = $request->request->get('programme', '');
                    $programme = $repository->findOneById($idProgramme);
                    $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");
                    //$idLieu = htmlspecialchars($_POST['lieu']);
                    $idLieu = $request->request->get('lieu', '');
                    $lieu = $repository->findOneById($idLieu);
                    $entrainement = new Entrainement;
                    $entrainement->setProgramme($programme);
                    $entrainement->setLieuRendezVous($lieu);
                    $entrainement->setEvenement($event);
                    $manager->persist($entrainement);
                    $manager->flush();
                    break;
                case '2': //Entrainement personnel
                    $entrainementPerso = new Entrainementpersonnel;
                    $entrainementPerso->setEvenement($event);
                    $manager->persist($entrainementPerso);
                    $manager->flush();
                    break;
                case '3': //Evenement divers
                    //$label = htmlspecialchars($_POST['label']);
                    $description = $request->request->get('desc', '');
                    $evenementDivers = new Evenementdivers;
                    $evenementDivers->setDescription($description);
                    $evenementDivers->setEvenement($event);
                    $manager->persist($evenementDivers);
                    $manager->flush();
                    break;
                case '4': //Sortie découverte
                    $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");
                    //$idLieu = htmlspecialchars($_POST['lieu']);
                    $idLieu = $request->request->get('lieu', '');
                    $lieu = $repository->findOneById($idLieu);
                    $sortieDecouverte = new Sortiedecouverte;
                    $sortieDecouverte->setLieuRendezVous($lieu);
                    $sortieDecouverte->setEvenement($event);
                    $manager->persist($sortieDecouverte);
                    $manager->flush();
                    break;
                case '5': //Course officielle
                    $repository=$manager->getRepository("SiteTrailBundle:Courseofficielle");
                    $siteUrl = $request->request->get('siteUrl', '');
                    $courseOfficielle = new Courseofficielle();
                    $courseOfficielle->setSiteUrl($siteUrl);
                    $courseOfficielle->setEvenement($event);
                    $manager->persist($courseOfficielle);
                    $manager->flush();
                default:
                    break;
            }
            
            if($request->request->get('participants', '') != '')
            {
                foreach($request->request->get('participants', '') as $monParticipant)
                {
                    $repository=$manager->getRepository("SiteTrailBundle:Membre");
                    $idParticipant = htmlspecialchars($monParticipant);
                    $userParticip = $repository->findOneById($idParticipant);
                    $participant = new Participants;
                    $participant->setMembre($userParticip);
                    $participant->setEvenement($event);
                    $manager->persist($participant);
                    $manager->flush();
                }
            }

            $request->getSession()->getFlashBag()->add('notice', 'Evènement ajouté');

            return $this->redirect($this->generateUrl('site_trail_evenement'));
        }
        
        $manager=$this->getDoctrine()->getManager();
        $repository=$manager->getRepository("SiteTrailBundle:Programme");        
        $listeProgramme = $repository->findAll();
        $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");        
        $listeLieuRendezVous = $repository->findAll();
       
        $query = $manager->createQuery(
            'SELECT u
            FROM SiteTrailBundle:Membre u
            WHERE u.id != :createur'
        )->setParameter('createur', $idUser);
        $listeUser = $query->getResult();        
        
        $formulaire = $this->get("templating")->render("SiteTrailBundle:Event:modifEventForm.html.twig", array(
                                                            'listeProgramme' => $listeProgramme,
                                                            'listeLieuRendezVous' => $listeLieuRendezVous,
                                                            'listeUser' => $listeUser,
                                                            'form' => $form->createView(),
                                                            'idClasse' => $idClasse
                                                        ));
        
        return new Response($formulaire);
    }
}
