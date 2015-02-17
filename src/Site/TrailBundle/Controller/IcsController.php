<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Ics;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Site\TrailBundle\Entity\Evenement;
use Site\TrailBundle\Entity\User;


class IcsController extends Controller
{
    public function indexAction($id, $dateDebut='', $dateFin='')
    {
        //Cas où on clique sur le lien télécharger le calendrier dans le site
        if($id == "default")
        {
            if($this->getUser())
            {
                $idUser = $this->getUser()->getId();
            }
            else
            {
                $idUser = 0;
            }
            
            $dateD = new \DateTime($dateDebut);
            date_time_set($dateD, 0, 0);
            $dateF = new \DateTime($dateFin);
            date_time_set($dateF, 23, 59, 59);
            
            /*$dateDebut = new \DateTime('now');
            date_time_set($dateDebut, 0, 0);
            $dateFin = new \DateTime('now');
            date_time_set($dateFin, 59, 59);
            date_add($dateFin, date_interval_create_from_date_string('14 days'));*/
        }
        else //Cas où on veut récupérer le calendrier "à l'exterieur" du site
        {
            $manager = $this->getDoctrine()->getManager();
            $repository=$manager->getRepository("SiteTrailBundle:User");
            $tokenics = htmlspecialchars($id);
            $utilisateur = $repository->findOneByTokenics($tokenics);
            $idUser = $utilisateur->getId();
            //$dateDebut = new \DateTime("now");
            //date_time_set($dateDebut, 0, 0);
            $dateD = '';
            $dateF = '';
        }        
        
        $mesEvenements = CalendrierController::getAllEventFrom($idUser, $this->getDoctrine()->getManager(), $dateD, $dateF);

        $ics = new Ics($mesEvenements);        
        
        return new Response($ics->show());
        //return new Response("e");
    }
    
    public function icsFormAction()
    {        
        $content = $this->get("templating")->render("SiteTrailBundle:Calendrier:icsForm.html.twig");
        
        return new Response($content);
    }
}
