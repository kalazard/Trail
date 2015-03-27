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
    
    public function newsAction($slug = NULL)
    {
		$content = $this->get("templating")->render("SiteTrailBundle:Home:news.html.twig"); 
	
		if($slug != NULL)
		{
			$content = $this->get("templating")->render("SiteTrailBundle:Home:anews.html.twig"); 
		}
		
        return new Response($content);
    }
	
	public function clubAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Home:club.html.twig"); 
        return new Response($content);
    }
	
	public function trailAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Home:trail.html.twig"); 
        return new Response($content);
    }
	
	public function contactAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Home:contact.html.twig"); 
        return new Response($content);
    }
}