<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Utilisateur;
use Site\TrailBundle\Entity\News;
use Site\TrailBundle\Entity\Image;
use Site\TrailBundle\Entity\Role;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{
    //Affichage de la page d'accueil
    public function indexAction()
    {
		// Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		// Récupère le dossier des news
        $repository_news = $manager->getRepository("SiteTrailBundle:News");
		
		$qb = $manager->createQueryBuilder();
        $qb->select('news')
            ->from('SiteTrailBundle:News', 'news')
			->where('news.visibilite = :visibilite AND news.alias != :trail AND news.alias != :club')
			->orderBy('news.date', 'DESC')
			->setParameters(['visibilite' => 1, 'trail' => "le-trail", 'club' => "le-club"])
			->setMaxResults(2);

        $row = $qb->getQuery();
        $news = $row->getResult();		
		$news_one = $news[0];
		$news_two = $news[1];
		
		$trail = $repository_news->findOneBy(array('alias' => "le-trail"));
		$club = $repository_news->findOneBy(array('alias' => "le-club"));
	
        $content = $this->get("templating")->render("SiteTrailBundle:Home:index.html.twig", array(
			'news_one' => $news_one,
			'news_two' => $news_two,
			'trail' => $trail,
			'club' => $club,
		)); 
        return new Response($content);
    }   
    
    public function newsAction($slug = NULL)
    {
		$this->testDeDroits('Nouteautes');
	
		// Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		// Récupère le dossier des news
        $repository_news = $manager->getRepository("SiteTrailBundle:News");
		
		if($slug == NULL)
		{
			$query = $manager->createQuery('SELECT news FROM SiteTrailBundle:News news
              WHERE news.visibilite = :visibilite AND news.alias != :trail AND news.alias != :club
			  ORDER BY news.date DESC
			  ');
			$query->setParameters(['visibilite' => 1, 'trail' => "le-trail", 'club' => "le-club"]);
			$news = $query->getResult();

			$content = $this->get("templating")->render("SiteTrailBundle:Home:news.html.twig", array(
				'news' => $news
			)); 			
		}
		else
		{
			$query = $manager->createQuery('SELECT news FROM SiteTrailBundle:News news
              WHERE news.visibilite = :visibilite AND news.alias = :slug
			  ORDER BY news.date DESC
			  ');
			$query->setParameters(['visibilite' => 1, 'slug' => $slug]);
			$new = $query->getSingleResult();
			
			$content = $this->get("templating")->render("SiteTrailBundle:Home:anews.html.twig", array(
				'new' => $new
			)); 
		}
		
        return new Response($content);
    }
	
	public function clubAction()
    {
        // Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		$query = $manager->createQuery('SELECT news FROM SiteTrailBundle:News news
		  WHERE news.visibilite = :visibilite AND news.alias = :club
		  ORDER BY news.date DESC
		  ');
		$query->setParameters(['visibilite' => 1, 'club' => "le-club"]);
		$new = $query->getSingleResult();

		$content = $this->get("templating")->render("SiteTrailBundle:Home:club.html.twig", array(
			'new' => $new
		)); 	
		return new Response($content);		
    }
	
	public function trailAction()
    {
        // Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		$query = $manager->createQuery('SELECT news FROM SiteTrailBundle:News news
		  WHERE news.visibilite = :visibilite AND news.alias = :trail
		  ORDER BY news.date DESC
		  ');
		$query->setParameters(['visibilite' => 1, 'trail' => "le-trail"]);
		$new = $query->getSingleResult();

		$content = $this->get("templating")->render("SiteTrailBundle:Home:trail.html.twig", array(
			'new' => $new
		)); 	
		return new Response($content);		
    }
	
	public function contactAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Home:contact.html.twig"); 
        return new Response($content);
    }
	
	public function testDeDroits($permission)
	{
		$manager = $this->getDoctrine()->getManager();
		
		$repository_permissions = $manager->getRepository("SiteTrailBundle:Permission");
		
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
				throw $this->createNotFoundException("Vous n'avez pas accès à cette page");
			}
		}
	}
}
