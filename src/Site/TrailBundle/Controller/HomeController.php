<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Utilisateur;
use Site\TrailBundle\Entity\Role;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{
    //Affichage de la page d'accueil
    public function indexAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Home:index.html.twig"); 
        return new Response($content);
    }   
    
    public function testAction()
    {
        $clientSOAP = new \SoapClient(null, array(
                    'uri' => "http://localhost/carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

                //On appel la méthode du webservice qui permet de se connecter
                $response = $clientSOAP->__call('zizitede', array());
        return new Response($response);
    }
}

