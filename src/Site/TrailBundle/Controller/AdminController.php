<?php namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\TrailBundle\Entity\Permission;
use Site\TrailBundle\Entity\Membre;
use Site\TrailBundle\Entity\Image;
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
		$this->testDeDroits('Administration');
		
		$content = $this->get("templating")->render("SiteTrailBundle:Admin:index.html.twig"); 
        return new Response($content);
    }
	
	// Gestion des permissions
	public function aclAction(Request $request)
	{
		$this->testDeDroits('Administration');
	
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
		$this->testDeDroits('Administration');
		
		// Récupère le manager de doctrine
		$manager = $this->getDoctrine()->getManager();
		
		// Récupère le dossier des news
        $repository_news = $manager->getRepository("SiteTrailBundle:News");
		
		$query = $manager->createQuery('SELECT news FROM SiteTrailBundle:News news
			  ORDER BY news.date DESC, news.id DESC
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
		$this->testDeDroits('Administration');
		
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
			if($new_alias == 'le-trail' || $new_alias == 'le-club')
			{
				$form = $this->createFormBuilder()
					->add('image', 'file', array('label'  => 'Image', 'required' => false))
					->add('titre', 'text', array('label'  => 'Titre', 'attr' => array('class' => 'form-control', 'disabled' => true), 'data' => $new->getTitre()))
					->add('visibilite', 'choice', array('choices' => array('0' => 'Non visible', '1' => 'Visible'), 'data' => $new->getVisibilite(), 'attr' => array('class' => 'form-control')))
					->add('texte', 'textarea', array('label'  => 'Texte', 'attr' => array('class' => 'form-control'), 'data' => $new->getTexte()))
					->add('valider', 'submit', array('attr' => array('class' => 'btn btn-warning move-bottom-sm')))
					->getForm();
			}
			else
			{
				$form = $this->createFormBuilder()
					->add('image', 'file', array('label'  => 'Image', 'required' => false))
					->add('titre', 'text', array('label'  => 'Titre', 'attr' => array('class' => 'form-control'), 'data' => $new->getTitre()))
					->add('visibilite', 'choice', array('choices' => array('0' => 'Non visible', '1' => 'Visible'), 'data' => $new->getVisibilite(), 'attr' => array('class' => 'form-control')))
					->add('texte', 'textarea', array('label'  => 'Texte', 'attr' => array('class' => 'form-control'), 'data' => $new->getTexte()))
					->add('valider', 'submit', array('attr' => array('class' => 'btn btn-warning move-bottom-sm')))
					->getForm();
			}
				
				
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
				
				if($form->get('image')->getData() != null)
				{
					$picture = new Image();
					$picture = $this->upload_image($form->get('image')->getData(), $form->get('titre')->getData(), $author);
					if($picture) $new->setImage($picture);
				}
				
				$manager->persist($new);
				$manager->flush();
				
				$manager->refresh($new);
				
				return $this->redirect($this->generateUrl('site_trail_news_manager', array('new_alias' => $new->getAlias())));
			}	
			
			$view = array(
				'new' => $new,
				'form' => $form->createView()
			);
			
			if($new->getImage()->getId() != 0)
			{
				$repository_images = $manager->getRepository("SiteTrailBundle:Image");
				$image = $repository_images->findOneBy(array('id' => $new->getImage()->getId()));
				$view['img'] = $image->getPath();
			}
			if(!empty($new))
			{
				$view['new'] = $new;
			}
				
			$content = $this->get("templating")->render("SiteTrailBundle:Admin:manager.html.twig", $view); 
		}
		else
		{
			// Créer le formulaire
			$form = $this->createFormBuilder()
				->add('image', 'file', array('label'  => 'Image', 'required' => false))
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
				
				$picture = new Image();
				$picture = $this->upload_image($form->get('image')->getData(), $form->get('titre')->getData(), $author);
				if($picture) $new->setImage($picture);
				
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
	
	public function upload_image($image, $titre, $auteur) {
		$dateTime = new DateTime('NOW');
		
		$for_extension = explode('.', $image->getClientOriginalName());
		$extension = $for_extension[Count($for_extension)-1];
		
		$fileName = "image" . date_format($dateTime, 'U') . $extension;
		
		$image->move($this->container->getParameter("upload_directory"), $fileName);
		
		list($width, $height, $type, $attr) = getimagesize($this->container->getParameter("upload_directory").$fileName);

		$manager = $this->getDoctrine()->getManager();

		//On rajoute l'image dans la base de données
		$description = "Image de l'article " . $titre;
		$poids = $image->getMaxFilesize();
		$taille = $width . 'x' .$height;
		
		$repository_category = $manager->getRepository("SiteTrailBundle:Categorie");
		$categorie = $repository_category->find(2);
		
		$newImage = new Image();
		$newImage->setTitre($titre);
		$newImage->setDescription($description);
		$newImage->setPoids($poids);
		$newImage->setTaille($taille);
		$newImage->setAuteur($auteur);
		$newImage->setCategorie($categorie);
		$newImage->setPath($this->container->getParameter("img_path").$fileName);
		
		$manager->persist($newImage);
		$manager->flush();
			
		return $newImage;
    }
	
	public function convert_to_alias($chaine)
	{
		$chaine = strtr($chaine, '@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿABCDEFGHIJKLMNOPQRSTUVWXYZ',
								'aaaaaaaceeeeiiiiooooouuuuyaaaaaaceeeeiiiioooooouuuuyyabcdefghijklmnopqrstuvwxyz');
		$chaine = preg_replace("#[^a-zA-Z0-9 ]#", "", $chaine);
		return strtr($chaine, ' ', '-');
	}
	
	// Suppression d'une news
    public function suppressionNewsAction($new_alias = NULL)
    {
		$this->testDeDroits('Administration');
		
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
