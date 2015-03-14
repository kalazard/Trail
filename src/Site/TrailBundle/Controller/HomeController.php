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
}

