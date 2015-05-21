<?php

namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\TrailBundle\Entity\Poi;
use Site\TrailBundle\Entity\Coordonnees;
use Site\TrailBundle\Entity\TypeLieu;
use Site\TrailBundle\Entity\Icone;
use Site\TrailBundle\Entity\Itiniraire;
use Site\TrailBundle\Entity\Gpx;
use Site\TrailBundle\Entity\DifficulteParcours;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\DateTime;


class MapController extends Controller
{
    public function indexAction()
    {
      
        
        $content = $this->get("templating")->render("SiteTrailBundle:Map:index.html.twig");
        
        
        return new Response($content);
    }

    public function createRouteAction(Request $request)
    {
      if ($request->isXMLHttpRequest()) 
      {
        $manager=$this->getDoctrine()->getManager();
        $repositoryDiff=$manager->getRepository("SiteTrailBundle:DifficulteParcours");

        $gpx = new Gpx();
        $gpx->setPath("test");
        $diff = $repositoryDiff->find($request->request->get("difficulte",""));

        $route = new Itiniraire();
        $route->setDate(new \DateTime('now'));
        $route->setLongueur($request->request->get("longueur","120"));
        $route->setDenivele($request->request->get("elevation","130"));
        $route->setItiniraire($gpx);
        $route->setNom($request->request->get("nom",""));
        $route->setNumero($request->request->get("numero",""));
        $route->setTypechemin($request->request->get("typechemin",""));
        $route->setCommentaire($request->request->get("commentaire",""));
        $route->setDifficulté($diff);

        $manager->persist($route);
        $manager->persist($gpx);
        $manager->flush();
        return new JsonResponse(array('data' => 'Itinéraire Crée'),200);
      }

      return new Response('This is not ajax!', 400);
    }


    public function createPoiAction(Request $request)
    {
      /*if ($request->isXMLHttpRequest()) 
      {*/
        $manager=$this->getDoctrine()->getManager();

        /*$repositoryIcon=$manager->getRepository("SiteTrailBundle:Icone");
        $icone = $repositoryIcon->find($request->request->get("idicone",""));*/
        $repositoryTypeLieu=$manager->getRepository("SiteTrailBundle:TypeLieu");
        $typelieu = $repositoryTypeLieu->find($request->request->get("idlieu",""));

        $coord = new Coordonnees();
        $coord->setLongitude($request->request->get("longitude",1.0));
        $coord->setLatitude($request->request->get("latitude",1.0));
        $coord->setAltitude($request->request->get("altitude",1.0));

        $poi = new Poi();
        $poi->setTitre($request->request->get("titre","MonPoi"));
        $poi->setDescription($request->request->get("description","Ceci est mon poi"));
        $poi->setCoordonnees($coord);
        $poi->setLieu($typelieu);

        $manager->persist($coord);
        $manager->persist($typelieu);
        $manager->persist($poi);
        $manager->flush();
        return new JsonResponse(array('data' => 'Poi Crée'),200);
      }
/*
      return new Response('This is not ajax!', 400);
    }*/
	
	public function uploadAction()
	{		
		$content = $this->get("templating")->render("SiteTrailBundle:Map:upload.html.twig");
		return new Response($content);
	}

    public function saveTypelieuAction()
    {       
        $content = $this->get("templating")->render("SiteTrailBundle:Map:saveTypelieu.html.twig");
        return new Response($content);
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

