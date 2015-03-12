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
        //$repository=$manager->getRepository("SiteTrailBundle:Itineraire");
        //$listPoints = $request->request->get("points","test");
        $route = new Itiniraire();
        $route->setDate(new \DateTime('now'));
        $route->setLongueur($request->request->get("longueur","120"));
        $route->setDenivele($request->request->get("elevation","130"));
        /*$route->setLongueur("100");
        $route->setDenivele("120");*/
        $gpx = new Gpx();
        $gpx->setPath("test");
        $route->setItiniraire($gpx);
        $route->setNom($request->request->get("nom","test"));
        $route->setNumero($request->request->get("numero","120"));
        $route->setTypechemin($request->request->get("typechemin","test"));
        $route->setCommentaire($request->request->get("commentaire","test"));
        $diff = new DifficulteParcours();
        $diff->setNiveauDifficulte(10);
        $diff->setLabel($request->request->get("difficulte","test"));
        $route->setDifficulte($diff);
        $manager->persist($route);
        $manager->persist($gpx);
        $manager->persist($diff);
        $manager->flush();
        return new JsonResponse(array('data' => 'Itinéraire Crée'),200);
      }

      return new Response('This is not ajax!', 400);
    }


    public function createPoiAction(Request $request)
    {
      if ($request->isXMLHttpRequest()) 
      {
        $manager=$this->getDoctrine()->getManager();
        $poi = new Poi();
        $poi->setTitre($request->request->get("titre","MonPoi"));
        $poi->setDescription($request->request->get("description","Ceci est mon poi"));
        $coord = new Coordonnees();
        $coord->setLongitude($request->request->get("longitude",1.0));
        $coord->setLatitude($request->request->get("latitude",1.0));
        $coord->setAltitude($request->request->get("altitude",1.0));
        $poi->setCoordonnees($coord);
        $icone = new Icone();
        $icone->setPath("test");
        $lieu = new TypeLieu();
        $lieu->setLabel("eau");
        $lieu->setIcone($icone);
        $poi->setLieu($lieu);
        $manager->persist($icone);
        $manager->persist($coord);
        $manager->persist($lieu);
        $manager->persist($poi);
        $manager->flush();
        return new JsonResponse(array('data' => 'Poi Crée'),200);
      }

      return new Response('This is not ajax!', 400);
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

