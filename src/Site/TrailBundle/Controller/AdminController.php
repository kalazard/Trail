<?php namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\TrailBundle\Entity\Permission;
use Site\TrailBundle\Entity\Membre;
use Site\TrailBundle\Entity\Role;
use Site\TrailBundle\Entity\News;
use \DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends Controller
{
    // Liste des liens d'admin
    public function indexAction()
    {
		
    }
	
	// Gestion des permissions
	public function aclAction(Request $request)
	{
		// Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		$repository_permissions = $manager->getRepository("SiteTrailBundle:Permission");
		$repository_roles = $manager->getRepository("SiteTrailBundle:Role");
		
		$permissions = $repository_permissions->findAll();
		$roles = $repository_roles->findAll();
		
		// Quand on post le formulaire
		if ($request->isMethod('post'))
		{
			// Récupère les données postées
			$params = $request->request->all();
			
			foreach($permissions as $permission)
			{
				foreach($permission->getRole() as $role)
				{
					$permission->removeRole($role);
					
					$manager->persist($permission);
					$manager->flush();
				}
			}
			
			foreach($roles as $role)
			{
				foreach($role->getPermission() as $permission)
				{
					$role->removePermission($permission);
					
					$manager->persist($role);
					$manager->flush();
				}
			}
			
			foreach($params as $key => $value)
			{
				$param_exploded = explode('-', $key);
				$permission_id = $param_exploded[1];
				$role_id = $param_exploded[2];
				$permission = $repository_permissions->findOneBy(array('id' => $permission_id));
				$role = $repository_roles->findOneBy(array('id' => $role_id));
				$permission->addRole($role);
				$role->addPermission($permission);
				
				$manager->flush();
			}
			
			return $this->redirect($this->generateUrl('site_trail_acl'));
		}
		
		$content = $this->get("templating")->render("SiteTrailBundle:Admin:acl.html.twig", array(
			'permissions' => $permissions,
			'roles' => $roles
		)); 
        return new Response($content);
	}
    
	// Liste des news
    public function listeNewsAction()
    {
		// Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		// Récupère le dossier des news
        $repository_news = $manager->getRepository("SiteTrailBundle:News");
		
		$query = $manager->createQuery('SELECT news FROM SiteTrailBundle:News news
			  ORDER BY news.date DESC
			  ');
		$news = $query->getResult();

        $content = $this->get("templating")->render("SiteTrailBundle:Admin:listnews.html.twig", array(
				'news' => $news
			)); 
        return new Response($content);
    }
	
	// Ajout/Modification d'une news
    public function gestionNewsAction($new_alias = NULL, Request $request)
    {		
		// Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		// Récupère le dossier des news
		$repository_news = $manager->getRepository("SiteTrailBundle:News");
		
		// Récupère le dossier des membres
		$repository_members = $manager->getRepository("SiteTrailBundle:Membre");
			
		if($new_alias != NULL)
		{
			$new = $repository_news->findOneBy(array('alias' => $new_alias));
		
			// Créer le formulaire
			$form = $this->createFormBuilder()
				->add('titre', 'text', array('label'  => 'Titre', 'attr' => array('class' => 'form-control'), 'data' => $new->getTitre()))
				->add('visibilite', 'choice', array('choices' => array('0' => 'Non visible', '1' => 'Visible'), 'data' => $new->getVisibilite(), 'attr' => array('class' => 'form-control')))
				->add('texte', 'textarea', array('label'  => 'Texte', 'attr' => array('class' => 'form-control'), 'data' => $new->getTexte()))
				->add('valider', 'submit', array('attr' => array('class' => 'btn btn-warning move-bottom-sm')))
				->getForm();
				
			$form->handleRequest($request);
			
			// Si le formulaire a été posté correctement
			if ($form->isValid())
			{
				$dateTime = new DateTime('NOW');
				
				$author = $repository_members->findOneBy(array('id' => $this->getUser()->getId()));
				
				$new->setTitre($form->get('titre')->getData());
				$new->setVisibilite($form->get('visibilite')->getData());
				$new->setAlias($this->convert_to_alias($form->get('titre')->getData()));
				$new->setTexte($form->get('texte')->getData());
				$new->setDate($dateTime);
				$new->setAuteur($author);
				
				$manager->persist($new);
				$manager->flush();
				
				$manager->refresh($new);
				
				return $this->redirect($this->generateUrl('site_trail_news_manager', array('new_alias' => $new->getAlias())));
			}	
				
			$content = $this->get("templating")->render("SiteTrailBundle:Admin:manager.html.twig", array(
				'new' => $new,
				'form' => $form->createView()
			)); 
		}
		else
		{
			// Créer le formulaire
			$form = $this->createFormBuilder()
				->add('titre', 'text', array('label'  => 'Titre', 'attr' => array('class' => 'form-control')))
				->add('visibilite', 'choice', array('choices' => array('0' => 'Non visible', '1' => 'Visible'), 'attr' => array('class' => 'form-control')))
				->add('texte', 'textarea', array('label'  => 'Texte', 'attr' => array('class' => 'form-control')))
				->add('valider', 'submit', array('attr' => array('class' => 'btn btn-warning move-bottom-sm')))
				->getForm();
				
			$form->handleRequest($request);
			
			// Si le formulaire a été posté correctement
			if ($form->isValid())
			{
				// Récupère le manager de doctrine
				$manager = $this->getDoctrine()->getManager();
				
				$dateTime = new DateTime('NOW');
				
				$author = $repository_members->findOneBy(array('id' => $this->getUser()->getId()));
				
				$new = new News;
				$new->setTitre($form->get('titre')->getData());
				$new->setVisibilite($form->get('visibilite')->getData());
				$new->setAlias($this->convert_to_alias($form->get('titre')->getData()));
				$new->setTexte($form->get('texte')->getData());
				$new->setDate($dateTime);
				$new->setAuteur($author);
				
				$manager->persist($new);
				$manager->flush();
				
				$manager->refresh($new);
				
				return $this->redirect($this->generateUrl('site_trail_news_manager', array('new_alias' => $new->getAlias())));
			}	

			$content = $this->get("templating")->render("SiteTrailBundle:Admin:manager.html.twig", array(
				'form' => $form->createView()
			)); 
		}
		
        return new Response($content);
    }
	
	public function convert_to_alias($chaine)
	{	
		$chaine = preg_replace("#[^a-zA-Z0-9 ]#", "", $chaine);
		//$text = strtr($text, 'ÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜİ', 'AAAAAACEEEEEIIIINOOOOOUUUUY');
		//$text = strtr($text, 'áàâäãåçéèêëíìîïñóòôöõúùûüıÿ', 'aaaaaaceeeeiiiinooooouuuuyy');
		return strtr($chaine, ' ', '-');
	}
	
	// Suppression d'une news
    public function suppressionNewsAction($new_alias = NULL)
    {
		// Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		// Récupère le dossier des news
		$repository_news = $manager->getRepository("SiteTrailBundle:News");
		
		// Puis la news précise
		$new = $repository_news->findOneBy(array('alias' => $new_alias));
		
		// La supprimer
		$manager->remove($new);
		$manager->flush();

		return $this->redirect($this->generateUrl('site_trail_news_list'));
    }
	
}
