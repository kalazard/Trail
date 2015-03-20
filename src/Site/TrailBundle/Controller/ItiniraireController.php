<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Itiniraire;
use Site\TrailBundle\Entity\DifficulteParcours;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ItiniraireController extends Controller 
{
	public function listAction()
	{
		//$user = $this->getUser();
		
		//si l'utilisateur courant est un membre
		
		$listItiniraire = array();
		
		//if()
		//{
			//on charge la liste des membres en utilisant la m�thode findAll de doctrine
			$repository = $this
			  ->getDoctrine()
			  ->getManager()
			  ->getRepository('SiteTrailBundle:Itiniraire')
			;

			$listItiniraire = $repository->findAll();
			
			//echo var_dump($listItiniraire);
			
		//}
	
		$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:ItiniraireDisplay.html.twig",array("resultats" => $listItiniraire));
		return new Response($content);
	}
	
	public function searchAction()
	{
		//$itineraire = $this->getUser();
		
		$search = "";
		if(isset($_POST['enter']))
		{
			$search = $_POST['enter'];
		}
		
		
		$listItiniraire = array();
			//on utilise un findBy pour r�cup�rer la liste des utilisateurs on fonction des donn�es de l'utilisateur
			if(!empty($search))
			{
				$repository = $this
					->getDoctrine()
					->getManager()
					->getRepository('SiteTrailBundle:Itiniraire')
				;

				$listItiniraire['nom'] = $repository->findBy(array('nom' => $search));
				
				$listItiniraire['typechemin'] = $repository->findBy(array('typechemin' => $search));

			}

		$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:SearchItiniraire.html.twig",array("resultats" => $listItiniraire));
		return new Response($content);
	}

	public function getByIdAction(Request $request)
	{
		//$itineraire = $this->getUser();
		
		//$search = "";
		/*if(isset($_POST['enter']))
		{
			$search = $_POST['enter'];
		}*/
		$search = $request->query->get("id");
		
		$listItiniraire = array();
			//on utilise un findBy pour r�cup�rer la liste des utilisateurs on fonction des donn�es de l'utilisateur
			if(!empty($search))
			{
				$repository = $this
					->getDoctrine()
					->getManager()
					->getRepository('SiteTrailBundle:Itiniraire')
				;

				$listItiniraire['id'] = $repository->findBy(array('id' => $search));
				
				//$listItiniraire['typechemin'] = $repository->findBy(array('typechemin' => $search));

			}

		$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:ItiniraireDisplay.html.twig",array("resultats" => $listItiniraire));
		return new Response($content);
	}

}


?>