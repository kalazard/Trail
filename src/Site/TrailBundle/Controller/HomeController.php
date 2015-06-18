<?php

namespace Site\TrailBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\TrailBundle\Entity\Utilisateur;
use Site\TrailBundle\Entity\News;
use Site\TrailBundle\Entity\Image;
use Site\TrailBundle\Entity\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{
    //Affichage de la page d'accueil
    public function indexAction(Request $request)
    {
		$this->testDeDroits('Accueil');
	
		// Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		if ($request->isMethod('post'))
		{
			// Si un des champs obligatoire est vide
			if($request->get('category') == '' ||  $request->get('subject') == '' || $request->get('email') == ''
			|| $request->get('firstname') == '' || $request->get('name') == '' || $request->get('message') == '')
			{
				$this->get('session')->getFlashBag()->add('message-error', 'Aucun champs ne peut Ãªtre vide');
				return $this->redirect($this->generateUrl('site_trail_homepage_empty'));
			}
			
			// Envoi un mail
			$message = \Swift_Message::newInstance()
			->setSubject($request->get('category') . ' : ' . $request->get('subject'))
			->setTo('noreply.trail@gmail.com')
			->setFrom(array($request->get('email') => $request->get('firstname') . ' ' . $request->get('name')))
			->setBody($request->get('email') . ' : ' . $request->get('message'));

			$this->get('mailer')->send($message);
		}
		
		// Récupère le dossier des news
        $repository_news = $manager->getRepository("SiteTrailBundle:News");
		
		$qb = $manager->createQueryBuilder();
        $qb->select('news')
            ->from('SiteTrailBundle:News', 'news')
			->where('news.visibilite = :visibilite AND news.alias != :trail AND news.alias != :club')
			->orderBy('news.date', 'DESC')
			->orderBy('news.id', 'DESC')
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
		$repository_images = $manager->getRepository("SiteTrailBundle:Image");
		
		if($slug == NULL)
		{
			$query = $manager->createQuery('SELECT news FROM SiteTrailBundle:News news
              WHERE news.visibilite = :visibilite AND news.alias != :trail AND news.alias != :club
			  ORDER BY news.date DESC
			  ');
			$query->setParameters(['visibilite' => 1, 'trail' => "le-trail", 'club' => "le-club"]);
			$news = $query->getResult();
			
			$img = array();
			foreach($news as $new)
			{
				if($new->getImage()->getId() != 0)
				{
					$image = $repository_images->findOneBy(array('id' => $new->getImage()->getId()));
					$img[$new->getId()] = $image->getPath();
				}
			}
			
			$content = $this->get("templating")->render("SiteTrailBundle:Home:news.html.twig", array(
				'news' => $news,
				'img' => $img
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
			
			$view = array(
				'new' => $new
			);
			
			if($new->getImage()->getId() != 0)
			{
				$image = $repository_images->findOneBy(array('id' => $new->getImage()->getId()));
				$view['img'] = $image->getPath();
			}
			
			$content = $this->get("templating")->render("SiteTrailBundle:Home:anews.html.twig", $view); 	
		}
		
        return new Response($content);
    }
	
	public function clubAction()
    {
		$this->testDeDroits('LeClub');
		
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
		$this->testDeDroits('LeTrail');
		
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
	
	public function contactAction(Request $request)
    {
		$this->testDeDroits('Contact');
		
		if ($request->isMethod('post'))
		{
			// Si un des champs obligatoire est vide
			if($request->get('category') == '' ||  $request->get('subject') == '' || $request->get('email') == ''
			|| $request->get('firstname') == '' || $request->get('name') == '' || $request->get('message') == '')
			{
				$this->get('session')->getFlashBag()->add('message-error', 'Aucun champs ne peut Ãªtre vide');
				return $this->redirect($this->generateUrl('site_trail_contact'));
			}
			
			// Envoi un mail
			$message = \Swift_Message::newInstance()
			->setSubject($request->get('category') . ' : ' . $request->get('subject'))
			->setTo('noreply.trail@gmail.com')
			->setFrom(array($request->get('email') => $request->get('firstname') . ' ' . $request->get('name')))
			->setBody($request->get('email') . ' : ' . $request->get('message'));

			$this->get('mailer')->send($message);
		}
		
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
				throw $this->createNotFoundException("Vous n'avez pas acces a cette page");
			}
		}
	}
}
