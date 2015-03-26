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

use Symfony\Component\HttpFoundation\Response;


class EvenementController extends Controller
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
        
        $listeEvenement = EvenementController::getAllEventFrom($idUser, $this->getDoctrine()->getManager());

        $content = $this->get("templating")->render("SiteTrailBundle:Event:calendrier.html.twig", array(
                                                    'listeEvenement' => $listeEvenement));
        
        return new Response($content);
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
                ->add('titre', 'text')
                ->add('description', 'text')
                ->add('lienKid', 'url')
                ->add('status', 'text')
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
                    $label = $request->request->get('label', '');
                    $evenementDivers = new Evenementdivers;
                    $evenementDivers->setLabel($label);
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
                    $courseOfficielle->setSiteUtl($siteUrl);
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
                    $participant->setUser($userParticip);
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
    
    public function afficherDetailEvenementAction()
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
            default:
                break;
        }
        
        $resp = $this->get("templating")->render("SiteTrailBundle:Event:detailEvenement.html.twig", array(
                                                            'evenement' => $evenement,
                                                            'idClasse' => $idClasse
                                                        ));
        
        return new Response($resp);
    }
}