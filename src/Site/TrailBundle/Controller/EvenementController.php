<?php namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\TrailBundle\Entity\Evenement;
use Site\TrailBundle\Entity\Membre;
use Site\TrailBundle\Entity\Entrainement;
use Site\TrailBundle\Entity\Entrainementpersonnel;
use Site\TrailBundle\Entity\Evenementdivers;
use Site\TrailBundle\Entity\Sortiedecouverte;
use Site\TrailBundle\Entity\Programme;
use Site\TrailBundle\Entity\Lieurendezvous;
use Site\TrailBundle\Entity\Participants;
use Site\TrailBundle\Entity\Participation;
use Site\TrailBundle\Entity\Courseofficielle;
use Site\TrailBundle\Entity\Status;

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
    
    public static function getEventFrom($idUser, $em, $dateDebut='', $dateFin='')
    {
        $listeEvenement = array();
        $listeEntrainement = array();
        $listeEntrainementPersonnel = array();
        $listeEvenementDivers = array();
        $listeSortieDecouverte = array();
        $listeCourseOfficielle = array();
        $bonusWhere = '';
        
        //Si on souhaite selectionner les évènements dans une certaine période (pour les ICS)
        if($dateDebut != '')
        {
            $bonusWhere = "AND e.datedebut >= '" . date_format($dateDebut, 'Y-m-d H:i:s') . "' ";
        }
        
        if($dateFin != '')
        {
            $bonusWhere .= "AND e.datedebut <= '" . date_format($dateFin, 'Y-m-d H:i:s') . "' ";
        }
        
        //Récupération des objets entrainement avec le status de participation
        $reqE = "SELECT en ";
        $reqE .= "FROM SiteTrailBundle:Entrainement en, SiteTrailBundle:Evenement e ";
        $reqE .= "WHERE en.evenement = e.id ";
        $reqE .= $bonusWhere;
        $queryE = $em->createQuery($reqE);
        $listeE = $queryE->getResult();
        
        //Si l'utilisateur est connecté on récupère sa participation également
        if($idUser > 0)
        {
            foreach($listeE as $ent)
            {
                $reqP = "SELECT pion ";
                $reqP .= "FROM SiteTrailBundle:Participants pant, SiteTrailBundle:Participation pion ";
                $reqP.= "WHERE pant.membre = ".$idUser;
                $reqP .= " AND pant.evenement = ".$ent->getEvenement()->getId();
                $reqP .= " AND pant.participation = pion.id";
                $queryP = $em->createQuery($reqP);
                $listeP = $queryP->getOneOrNullResult();            
                $listeRes = array($ent, $listeP);
                $listeEntrainement[] = $listeRes;
            }

            $listeEvenement[] = $listeEntrainement;
        }
        else
        {
            $tabTmp = array();

            foreach($listeE as $ent)
            {
                $tabTmp[] = array($ent);
            }

            $listeEvenement[] = $tabTmp; 
        }
        
        
        //Récupération des objets entrainementpersonnel avec le status de participation
        $reqE = "SELECT ep ";
        $reqE .= "FROM SiteTrailBundle:Entrainementpersonnel ep, SiteTrailBundle:Evenement e ";
        $reqE .= "WHERE ep.evenement = e.id ";
        $reqE .= $bonusWhere;
        $queryE = $em->createQuery($reqE);
        $listeE = $queryE->getResult();
        
        //Si l'utilisateur est connecté on récupère sa participation également
        if($idUser > 0)
        {
            foreach($listeE as $entPerso)
            {
                $reqP = "SELECT pion ";
                $reqP .= "FROM SiteTrailBundle:Participants pant, SiteTrailBundle:Participation pion ";
                $reqP.= "WHERE pant.membre = ".$idUser;
                $reqP .= " AND pant.evenement = ".$entPerso->getEvenement()->getId();
                $reqP .= " AND pant.participation = pion.id";
                $queryP = $em->createQuery($reqP);
                $listeP = $queryP->getOneOrNullResult();            
                $listeRes = array($entPerso, $listeP);
                $listeEntrainementPersonnel[] = $listeRes;
            }

            $listeEvenement[] = $listeEntrainementPersonnel;
        }
        else
        {
            $tabTmp = array();

            foreach($listeE as $ent)
            {
                $tabTmp[] = array($ent);
            }

            $listeEvenement[] = $tabTmp;
        }
        
        //Récupération des objets evenementDivers avec le status de participation
        $reqE = "SELECT ed ";
        $reqE .= "FROM SiteTrailBundle:Evenementdivers ed, SiteTrailBundle:Evenement e ";
        $reqE .= "WHERE ed.evenement = e.id ";
        $reqE .= $bonusWhere;
        $queryE = $em->createQuery($reqE);
        $listeE = $queryE->getResult();
        
        //Si l'utilisateur est connecté on récupère sa participation également
        if($idUser > 0)
        {
            foreach($listeE as $entPerso)
            {
                $reqP = "SELECT pion ";
                $reqP .= "FROM SiteTrailBundle:Participants pant, SiteTrailBundle:Participation pion ";
                $reqP.= "WHERE pant.membre = ".$idUser;
                $reqP .= " AND pant.evenement = ".$entPerso->getEvenement()->getId();
                $reqP .= " AND pant.participation = pion.id";
                $queryP = $em->createQuery($reqP);
                $listeP = $queryP->getOneOrNullResult();            
                $listeRes = array($entPerso, $listeP);
                $listeEvenementDivers[] = $listeRes;
            }

            $listeEvenement[] = $listeEvenementDivers;
        }
        else
        {
            $tabTmp = array();

            foreach($listeE as $ent)
            {
                $tabTmp[] = array($ent);
            }

            $listeEvenement[] = $tabTmp;
        }
        
        //Récupération des objets sortieDecouverte avec le status de participation
        $reqE = "SELECT sd ";
        $reqE .= "FROM SiteTrailBundle:Sortiedecouverte sd, SiteTrailBundle:Evenement e ";
        $reqE .= "WHERE sd.evenement = e.id ";
        $reqE .= $bonusWhere;
        $queryE = $em->createQuery($reqE);
        $listeE = $queryE->getResult();
        
        //Si l'utilisateur est connecté on récupère sa participation également
        if($idUser > 0)
        {
            foreach($listeE as $entPerso)
            {
                $reqP = "SELECT pion ";
                $reqP .= "FROM SiteTrailBundle:Participants pant, SiteTrailBundle:Participation pion ";
                $reqP.= "WHERE pant.membre = ".$idUser;
                $reqP .= " AND pant.evenement = ".$entPerso->getEvenement()->getId();
                $reqP .= " AND pant.participation = pion.id";
                $queryP = $em->createQuery($reqP);
                $listeP = $queryP->getOneOrNullResult();            
                $listeRes = array($entPerso, $listeP);
                $listeSortieDecouverte[] = $listeRes;
            }

            $listeEvenement[] = $listeSortieDecouverte;
        }
        else
        {
            $tabTmp = array();

            foreach($listeE as $ent)
            {
                $tabTmp[] = array($ent);
            }

            $listeEvenement[] = $tabTmp;
        }
        
        //Récupération des objets courseOfficielle avec le status de participation
        $reqE = "SELECT co ";
        $reqE .= "FROM SiteTrailBundle:Courseofficielle co, SiteTrailBundle:Evenement e ";
        $reqE .= "WHERE co.evenement = e.id ";
        $reqE .= $bonusWhere;
        $queryE = $em->createQuery($reqE);
        $listeE = $queryE->getResult();
        
        //Si l'utilisateur est connecté on récupère sa participation également
        if($idUser > 0)
        {
            foreach($listeE as $entPerso)
            {
                $reqP = "SELECT pion ";
                $reqP .= "FROM SiteTrailBundle:Participants pant, SiteTrailBundle:Participation pion ";
                $reqP.= "WHERE pant.membre = ".$idUser;
                $reqP .= " AND pant.evenement = ".$entPerso->getEvenement()->getId();
                $reqP .= " AND pant.participation = pion.id";
                $queryP = $em->createQuery($reqP);
                $listeP = $queryP->getOneOrNullResult();            
                $listeRes = array($entPerso, $listeP);
                $listeCourseOfficielle[] = $listeRes;
            }

            $listeEvenement[] = $listeCourseOfficielle;
        }
        else
        {
            $tabTmp = array();

            foreach($listeE as $ent)
            {
                $tabTmp[] = array($ent);
            }

            $listeEvenement[] = $tabTmp;
        }
        
        return $listeEvenement;
    }
    
    //Affiche le calendrier
    public function indexAction()
    {
        if($this->getUser())
        {
            $idUser = $this->getUser()->getId();            
            $listeEvenement = EvenementController::getEventFrom($idUser, $this->getDoctrine()->getManager());
        }
        else
        {
            $listeEvenement = EvenementController::getEventFrom(0, $this->getDoctrine()->getManager());
            
            //On retire les événements qui ne sont pas disponibles pour les non connectés
            $listeEvenement[0] = array();
            $listeEvenement[1] = array();
            $listeEvenement[2] = array();
            $listeEvenement[4] = array();
        }

        //On transforme les résultats en json            
        $listeEvenementJson = json_encode($listeEvenement);

        $view = $this->get("templating")->render("SiteTrailBundle:Event:calendrier.html.twig", array(
                                                            'listeEvenement' => $listeEvenementJson));

        return new Response($view);
    }
    
    public function showFormAddEventAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $this->getUser())
        {
            $dateCliquee = $request->request->get('dateCliquee', '');
            $idUser = $this->getUser()->getId();

            $event = new Evenement;
            $formBuilder = $this->get('form.factory')->createBuilder('form', $event);
            $formBuilder
                    ->setAction($this->generateUrl('site_trail_evenement_ajout'))
                    ->add('titre', 'text', array('max_length' => 255))
                    ->add('description', 'text', array('max_length' => 255))
                    ->add('date_debut', 'datetime', array(
                                        'data' => new \DateTime($dateCliquee)))
                    ->add('date_fin', 'datetime', array(
                                        'data' => new \DateTime($dateCliquee)));
            
            $form = $formBuilder->getForm();
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            
            if ($form->isValid()) 
            {
                $type = $request->request->get('type', '');
                $idCreateur = $this->getUser()->getId();
                $kid = $request->request->get('kid', '0');
                
                //Si l'utilisateur a coché oui, on créer un kid pour cet événement
                if($kid == 1)
                {
                    $clientSOAP = new \SoapClient(null, array(
                        'uri' => "http://130.79.214.167/Kidoikoiaki/web/app_dev.php/evenement",
                        'location' => "http://130.79.214.167/Kidoikoiaki/web/app_dev.php/evenement",
                        'trace' => true,
                        'exceptions' => true
                    ));
                    
                    //renvoie le token : mettre titre evenement
                    $token = json_decode($clientSOAP->__call('creerevenement', array('title' => $event->getTitre())));
                    
                    $event->setLienKid("http://130.79.214.167/Kidoikoiaki/web/app_dev.php/participants/".$token->token);
                }
                
                $repository = $manager->getRepository("SiteTrailBundle:Membre");
                $event->setCreateur($repository->findOneById($idCreateur));
                $event->setDateCreation(new \DateTime("now"));                
                $event->setAlias("");
                $repository = $manager->getRepository("SiteTrailBundle:Status");
                $event->setStatus($repository->findOneById(1)); //valeur par défaut
                $manager->persist($event);
                $manager->flush();

                switch ($type)
                {
                    case '1': //Entrainement
                        $repository = $manager->getRepository("SiteTrailBundle:Programme");
                        $idProgramme = $request->request->get('programme', '');
                        $programme = $repository->findOneById($idProgramme);
                        $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");
                        $idLieu = $request->request->get('lieu', '');
                        $lieu = $repository->findOneById($idLieu);
                        $entrainement = new Entrainement;
                        $entrainement->setProgramme($programme);
                        $entrainement->setLieuRendezVous($lieu);
                        $entrainement->setEvenement($event);
                        $manager->persist($entrainement);
                        break;
                    case '2': //Entrainement personnel
                        $entrainementPerso = new Entrainementpersonnel;
                        $entrainementPerso->setEvenement($event);
                        $manager->persist($entrainementPerso);
                        break;
                    case '3': //Evenement divers
                        //$label = htmlspecialchars($_POST['label']);
                        $description = $request->request->get('desc', '');
                        $evenementDivers = new Evenementdivers;
                        $evenementDivers->setDescription($description);
                        $evenementDivers->setEvenement($event);
                        $manager->persist($evenementDivers);
                        break;
                    case '4': //Sortie découverte
                        $repository = $manager->getRepository("SiteTrailBundle:Lieurendezvous");
                        //$idLieu = htmlspecialchars($_POST['lieu']);
                        $idLieu = $request->request->get('lieu', '');
                        $lieu = $repository->findOneById($idLieu);
                        $sortieDecouverte = new Sortiedecouverte;
                        $sortieDecouverte->setLieuRendezVous($lieu);
                        $sortieDecouverte->setEvenement($event);
                        $manager->persist($sortieDecouverte);
                        break;
                    case '5': //Course officielle
                        $repository = $manager->getRepository("SiteTrailBundle:Courseofficielle");
                        $siteUrl = $request->request->get('siteUrl', '');
                        $courseOfficielle = new Courseofficielle();
                        $courseOfficielle->setSiteUrl($siteUrl);
                        $courseOfficielle->setEvenement($event);
                        $manager->persist($courseOfficielle);
                    default:
                        break;
                }
                
                $manager->flush();

                //Ajout des participants (et participations) s'il y en a
                if($request->request->get('participants', '') != '')
                {
                    foreach($request->request->get('participants', '') as $monParticipant)
                    {
                        $repository = $manager->getRepository("SiteTrailBundle:Membre");
                        $idParticipant = htmlspecialchars($monParticipant);
                        $userParticip = $repository->findOneById($idParticipant);
                        $participation = new Participation();
                        $participation->setEtatInscription('enattente');
                        $participation->setResultat('');
                        $participation->setDivers('');
                        $manager->persist($participation);
                        $participant = new Participants();
                        $participant->setMembre($userParticip);
                        $participant->setEvenement($event);
                        $participant->setParticipation($participation);
                        $manager->persist($participant);
                        $manager->flush();
                    }
                }

                return $this->redirect($this->generateUrl('site_trail_evenement'));
            }

            $repository = $manager->getRepository("SiteTrailBundle:Programme");        
            $listeProgramme = $repository->findAll();
            $repository = $manager->getRepository("SiteTrailBundle:Lieurendezvous");        
            $listeLieuRendezVous = $repository->findAll();

            $repository = $manager->getRepository("SiteTrailBundle:Membre");        
            $listeMembre = $repository->findAll(); 
            
            //Création du select contenant les programmes
            $selectProgramme = '<div class="form-group">';
            $selectProgramme .= '<div class="row">';
            $selectProgramme .= '<label class="col-sm-3 control-label">Programme :</label>';
            $selectProgramme .= '<div class="col-sm-9">';
            $selectProgramme .= '<select name="programme" class="form-control">';
            foreach($listeProgramme as $unProgramme)
            {
                $selectProgramme .= '<option value="'.$unProgramme->getId().'">'.$unProgramme->getLabel().' ('.$unProgramme->getDuree()->format('h:i').')</option>';
            }
            $selectProgramme .= '</select>';
            $selectProgramme .= '</div>';
            $selectProgramme .= '</div>';
            $selectProgramme .= '</div>';       
            
            //Création du select contenant les lieuRendezVous
            $selectLieuRendezVous = '<div class="form-group">';
            $selectLieuRendezVous .= '<div class="row">';
            $selectLieuRendezVous .= '<label class="col-sm-3 control-label">Lieu de rendez-vous :</label>';
            $selectLieuRendezVous .= '<div class="col-sm-9">';
            $selectLieuRendezVous .= '<select name="lieu" class="form-control">';
            foreach($listeLieuRendezVous as $unLieuRendezVous)
            {
                $selectLieuRendezVous .= '<option value="'.$unLieuRendezVous->getId().'">'.$unLieuRendezVous->getTitre().'</option>';
            }
            $selectLieuRendezVous .= '</select>';
            $selectLieuRendezVous .= '</div>';
            $selectLieuRendezVous .= '</div>';
            $selectLieuRendezVous .= '</div>';
            
            $formulaire = $this->get("templating")->render("SiteTrailBundle:Event:formAddEvent.html.twig", array(
                                                                'selectProgramme' => $selectProgramme,
                                                                'selectLieuRendezVous' => $selectLieuRendezVous,
                                                                'listeUser' => $listeMembre,
                                                                'form' => $form->createView()
                                                            ));

            return new Response($formulaire);
        }
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }
    
    public function afficherDetailEvenementAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $idClasse = $request->request->get('idClasse', '2');
            $idObj = $request->request->get('idObj', '19');
            
            if($this->getUser())
            {
                $idUser = $this->getUser()->getId();
            }

            $manager=$this->getDoctrine()->getManager();
            
            switch ($idClasse)
            {
                case '1': //Entrainement  
                    $repository=$manager->getRepository("SiteTrailBundle:Entrainement");        
                    $evenement = $repository->findOneById($idObj);
                    break;
                case '2': //Entrainement personnel
                    $repository=$manager->getRepository("SiteTrailBundle:Entrainementpersonnel");        
                    $evenement = $repository->findOneById($idObj);
                    break;
                case '3': //Evenement divers
                    $repository=$manager->getRepository("SiteTrailBundle:Evenementdivers");        
                    $evenement = $repository->findOneById($idObj);
                    break;
                case '4': //Sortie découverte
                    $repository=$manager->getRepository("SiteTrailBundle:Sortiedecouverte");        
                    $evenement = $repository->findOneById($idObj);
                    break;
                case '5': //Course officielle 
                    $repository=$manager->getRepository("SiteTrailBundle:Courseofficielle");        
                    $evenement = $repository->findOneById($idObj);
                    break;
                default:
                    break;
            }
           
            $participation = '';
            
            if($this->getUser())
            {
                //Participation lié à cet événement
                $reqP = "SELECT pion ";
                $reqP .= "FROM SiteTrailBundle:Participants pant, SiteTrailBundle:Participation pion";
                $reqP.= " WHERE pant.membre = ".$idUser;
                $reqP .= " AND pant.evenement = ".$evenement->getEvenement()->getId();
                $reqP .= " AND pant.participation = pion.id";
                $queryP = $manager->createQuery($reqP);
                $participation = $queryP->getOneOrNullResult(); 
            }
                     

            $resp = $this->get("templating")->render("SiteTrailBundle:Event:detailEvenement.html.twig", array(
                                                                'evenement' => $evenement,
                                                                'idClasse' => $idClasse,
                                                                'participation' => $participation
                                                            ));

            return new Response($resp);
        }
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }
    
    public function supprEvenementAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $this->getUser())
        {
            $idClasse = $request->request->get('idClasse', '');
            $idEvenementDeClasse = $request->request->get('idObj', '');

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
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }
    
    public function modifEvenementAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $this->getUser())
        {      
            $idUser = $this->getUser()->getId();
            $idClasse = $request->request->get('idClasse', '');
            $idEvenementDeClasse = $request->request->get('idObj', '');

            $tabEvenements = EvenementController::getEvenementEtEvenementDeCategorie($this->getDoctrine()->getManager(), $idClasse, $idEvenementDeClasse);

            $evenementDeCategorie = $tabEvenements[0];
            $evenementAssocie = $tabEvenements[1];

            $formBuilder = $this->get('form.factory')->createBuilder('form', $evenementAssocie);
            $formBuilder
                    ->setAction($this->generateUrl('site_trail_evenement_modification'))
                    ->add('titre', 'text', array('max_length' => 255,
                                                    'data' => $evenementAssocie->getTitre()))
                    ->add('description', 'text', array('max_length' => 255,
                                                        'data' => $evenementAssocie->getDescription()))
                    ->add('lienKid', 'url', array('required' => false,
                                                    'max_length' => 255,
                                                    'data' => $evenementAssocie->getLienKid()))
                    ->add('date_debut', 'datetime', array(
                                        'data' => $evenementAssocie->getDateDebut()))
                    ->add('date_fin', 'datetime', array(
                                        'data' => $evenementAssocie->getDateFin()));

            $form = $formBuilder->getForm();
            $form->handleRequest($request);

            if ($form->isValid()) 
            {
                $type = $request->request->get('type', '');
                $idCreateur = $request->request->get('createur', '');

                $manager=$this->getDoctrine()->getManager();
                $repository=$manager->getRepository("SiteTrailBundle:Membre");
                $evenementAssocie->setCreateur($repository->findOneById($idCreateur));
                $evenementAssocie->setDateCreation(new \DateTime("now"));
                $evenementAssocie->setAlias("alias");

                $manager = $this->getDoctrine()->getManager();
                $manager->flush();

                switch ($type)
                {
                    case '1': //Entrainement
                        $repository=$manager->getRepository("SiteTrailBundle:Programme");
                        $idProgramme = $request->request->get('programme', '');
                        $programme = $repository->findOneById($idProgramme);
                        $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");
                        $idLieu = $request->request->get('lieu', '');
                        $lieu = $repository->findOneById($idLieu);
                        $evenementDeCategorie->setProgramme($programme);
                        $evenementDeCategorie->setLieuRendezVous($lieu);
                        $evenementDeCategorie->setEvenement($evenementAssocie);
                        $manager->flush();
                        break;
                    case '2': //Entrainement personnel
                        $evenementDeCategorie->setEvenement($evenementAssocie);
                        $manager->flush();
                        break;
                    case '3': //Evenement divers
                        $description = $request->request->get('desc', '');
                        $evenementDeCategorie->setDescription($description);
                        $evenementDeCategorie->setEvenement($evenementAssocie);
                        $manager->flush();
                        break;
                    case '4': //Sortie découverte
                        $repository=$manager->getRepository("SiteTrailBundle:Lieurendezvous");
                        $idLieu = $request->request->get('lieu', '');
                        $lieu = $repository->findOneById($idLieu);
                        $evenementDeCategorie->setLieuRendezVous($lieu);
                        $evenementDeCategorie->setEvenement($evenementAssocie);
                        $manager->flush();
                        break;
                    case '5': //Course officielle
                        $repository=$manager->getRepository("SiteTrailBundle:Courseofficielle");
                        $siteUrl = $request->request->get('siteUrl', '');
                        $evenementDeCategorie->setSiteUrl($siteUrl);
                        $evenementDeCategorie->setEvenement($evenementAssocie);
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
                        $participant->setEvenement($evenementAssocie);
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
                                                                'idClasse' => $idClasse,
                                                                'idObj' => $idEvenementDeClasse 
                                                            ));

            return new Response($formulaire);
        }
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }
    
    public function checkDateAction(Request $request)
    {
        $anneeD = $request->request->get('anneeD', '');
        $moisD = $request->request->get('moisD', '');
        $jourD = $request->request->get('jourD', '');
        $heureD = $request->request->get('heureD', '');
        $minuteD = $request->request->get('minuteD', '');
        $anneeF = $request->request->get('anneeF', '');
        $moisF = $request->request->get('moisF', '');
        $jourF = $request->request->get('jourF', '');
        $heureF = $request->request->get('heureF', '');
        $minuteF = $request->request->get('minuteF', '');
        
        $dateD = new \DateTime($anneeD."-".$moisD."-".$jourD." ".$heureD.":".$minuteD);
        $dateF = new \DateTime($anneeF."-".$moisF."-".$jourF." ".$heureF.":".$minuteF);
        
        //On vérifie la validité des deux dates 
        if(checkdate($moisD, $jourD, $anneeD) == false || checkdate($moisF, $jourF, $anneeF) == false)
        {
            return new Response("invalide");
        }
        
        if($dateD > $dateF)
        {
            return new Response("debutsuperieurfin");
        }
        
        return new Response("ok");
    }
    
    public function eventSearchFormAction()
    {
        $typeEvent = "";
        
        if($this->getUser())
        {
            $typeEvent = "<ul>";
            $typeEvent .= "<li><input type='checkbox' name='type[]' value='0' checked /> Tous les types</li>";
            $typeEvent .= "<li><input type='checkbox' name='type[]' value='1' /> Entrainement</li>";
            $typeEvent .= "<li><input type='checkbox' name='type[]' value='2' /> Entrainement personnel</li>";
            $typeEvent .= "<li><input type='checkbox' name='type[]' value='3' /> Evénements divers</li>";
            $typeEvent .= "<li><input type='checkbox' name='type[]' value='4' /> Sortie découverte</li>";
            $typeEvent .= "<li><input type='checkbox' name='type[]' value='5' /> Course officielle</li>";
            $typeEvent .= "</ul>";
        }
        else
        {
            $typeEvent = "<input type='checkbox' name='type[]' value='4' checked disabled />Sortie découverte";
        }

        $content = $this->get("templating")->render("SiteTrailBundle:Event:search.html.twig", array(
                                                        'typeEvent' => $typeEvent
                                                    ));

        return new Response($content);
    }
    
    public function eventSearchAction(Request $request)
    {
        $textDebug = "";
        
        if($request->isXmlHttpRequest())
        {
            $onCherchePar = $request->request->get('searchType', '');
            $resultats = array();
            
            switch($onCherchePar)
            {
                case 'type':
                    $typesEvenement = $request->request->get('type', '');                    
                    $resultats = EvenementController::getEventFrom(0, $this->getDoctrine()->getManager());
                    
                    if(!(in_array("0", $typesEvenement)))
                    {
                        foreach($resultats as $type => $evenement)
                        {
                            if(!(in_array($type+1, $typesEvenement)))
                            {
                                $resultats[$type] = array();
                            }
                        }
                    }       
                    
                    break;
                case 'date':
                    $interDebut = $request->request->get('dateDebut', '');
                    $interFin = $request->request->get('dateFin', '');
                    $resultats = EvenementController::getEventFrom(0, $this->getDoctrine()->getManager(), $interDebut, $interFin);
                    break;
                case 'typeEtDate':
                    $typesEvenement = $request->request->get('type', '');
                    $interDebut = new \DateTime($request->request->get('dateDebut', ''));
                    $interFin = new \DateTime($request->request->get('dateFin', ''));
                    $resultats = EvenementController::getEventFrom(0, $this->getDoctrine()->getManager(), $interDebut, $interFin);
                    
                    if(!(in_array("0", $typesEvenement)))
                    {
                        foreach($resultats as $type => $evenement)
                        {
                            if(!(in_array($type+1, $typesEvenement)))
                            {
                                $resultats[$type] = array();
                            }
                        }
                    }  
                    
                    break;
            }
            
            $resultatsJson = json_encode($resultats);            
            
            return new Response($resultatsJson);
        }
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }
}
