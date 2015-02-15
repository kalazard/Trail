<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{
    public function indexAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Home:index.html.twig");
        
        
        return new Response($content);
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

