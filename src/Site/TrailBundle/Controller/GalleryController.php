<?php namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GalleryController extends Controller
{
    public function indexAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Gallery:index.html.twig");
        return new Response($content);
    }
	
	public function categoryAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Gallery:category.html.twig");
        return new Response($content);
    }
	
	public function pictureAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Gallery:picture.html.twig");
        return new Response($content);
    }
}    