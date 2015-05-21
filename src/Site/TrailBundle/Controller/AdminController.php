<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Utilisateur;
use Site\TrailBundle\Entity\Role;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class AdminController extends Controller
{
    //Affichage de la page d'accueil
    public function indexAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Home:index.html.twig"); 
        return new Response($content);
    }   
    
	// Gestion des news
    public function newsAction($slug = NULL)
    {
		
    }
	
	// Gestion de la partie "le Trail"
    public function newsAction($slug = NULL)
    {
		
    }
	
	// Gestion de la partie "le Club"
    public function newsAction($slug = NULL)
    {
		
    }
	
}
