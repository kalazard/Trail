<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Ics;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Site\TrailBundle\Entity\Evenement;
use Site\TrailBundle\Entity\Membre;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class IcsController extends Controller
{
    public function indexAction($id, $dateDebut='', $dateFin='')
    {
		$this->testDeDroits('Calendrier');
		
        //Cas où on clique sur le lien télécharger le calendrier dans le site
        if($id == "default")
        {
            if($this->getUser())
            {
                $idUser = $this->getUser()->getId();
            }
            
            $dateD = new \DateTime($dateDebut);
            date_time_set($dateD, 0, 0);
            $dateF = new \DateTime($dateFin);
            date_time_set($dateF, 23, 59, 59);
        }
        else //Cas où on veut récupérer le calendrier "à l'exterieur" du site
        {
            $manager = $this->getDoctrine()->getManager();
            $repository=$manager->getRepository("SiteTrailBundle:Membre");
            $tokenics = htmlspecialchars($id);
            $utilisateur = $repository->findOneByTokenics($tokenics);
            $idUser = $utilisateur->getId();
            $dateD = '';
            $dateF = '';
        }        
        
        $mesEvenements = EvenementController::getEventFrom($idUser, $this->getDoctrine()->getManager(), $dateD, $dateF);

        $ics = new Ics($mesEvenements);        
        
        return new Response($ics->show());
    }
    
    public function icsFormAction(Request $request)
    { 
		$this->testDeDroits('Calendrier');
		
        if($request->isXmlHttpRequest())
        {
            $content = $this->get("templating")->render("SiteTrailBundle:Event:icsForm.html.twig");
        
            return new Response($content);
        }
        else
        {
				throw $this->createNotFoundException("Vous n'avez pas acces a cette page");
        }
        
    }
}
