<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\User;
use Site\TrailBundle\Entity\Role;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MemberDisplayController extends Controller 
{
	public function listAction()
	{
		$user = $this->getUser();
		
		//si l'utilisateur courant est un membre
		
		$listUsers = array();
		
		//if()
		//{
			//on charge la liste des membres en utilisant la méthode findAll de doctrine
			$repository = $this
			  ->getDoctrine()
			  ->getManager()
			  ->getRepository('SiteTrailBundle:user')
			;

			$listUsers = $repository->findAll();
			
			echo var_dump($listUsers);
			
		//}
	
		$content = $this->get("templating")->render("SiteTrailBundle:MemberDisplay:MemberDisplay.html.twig",array("resultats" => $listUsers));
		return new Response($content);
	}
	
	public function searchAction()
	{
		$user = $this->getUser();
		
		$search = "";
		if(isset($_POST['enter']))
		{
			$search = $_POST['enter'];
		}
		
		//si l'utilisateur courant est un membre
		
		$listUsers = array();
		
		//if()
		//{
			//on utilise un findBy pour récupérer la liste des utilisateurs on fonction des données de l'utilisateur
			if(!empty($search))
			{
				$repository = $this
					->getDoctrine()
					->getManager()
					->getRepository('SiteTrailBundle:user')
				;

				$listUsers['username'] = $repository->findBy(array('username' => $search));
				
				$listUsers['email'] = $repository->findBy(array('email' => $search));

			}
		//}
		
		
		
		$content = $this->get("templating")->render("SiteTrailBundle:MemberDisplay:SearchMember.html.twig",array("resultats" => $listUsers));
		return new Response($content);
	}
}


?>