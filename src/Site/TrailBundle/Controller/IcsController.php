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
    /**
     * Fonction qui renvoie un fichier ics
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * id : Savoir si on télécharge le fichier ics de l'exterieur du site ou non
     * dateDebut : Date de début des événements à mettre dans le fichier ICS
     * dateFin : Date de fin des événements à mettre dans le fichier ICS
     * </code>
     * 
     * @return Response 
     */
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
    
    /**
     * Fonction qui affiche le formulaire de choix de récupération du fichier ICS
     *
     * Cette méthode ne requiert pas de paramètres : 
     * 
     * @return Response 
     */
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

    public function testDeDroits($permission)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $repository_permissions = $manager->getRepository("SiteCartoBundle:Permission");
        
        $permissions = $repository_permissions->findOneBy(array('label' => $permission));

        if(Count($permissions->getRole()) != 0)
        {
            $list_role = array();
            foreach($permissions->getRole() as $role)
            {
                array_push($list_role, 'ROLE_'.$role->getLabel());
            }
            
            // Test l'accès de l'utilisateur
            if(!$this->isGranted($list_role))
            {
                throw $this->createNotFoundException("Vous n'avez pas acces a cette page");
            }
        }
    }
}
