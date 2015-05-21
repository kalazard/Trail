<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Utilisateur;
use Site\TrailBundle\Entity\Role;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class MemberDisplayController extends Controller 
{
	public function listAction()
	{
		$user = $this->getUser();
		
		//si l'utilisateur courant est un membre
		
		$listUsers = array();
		
		//if()
		//{
			//on charge la liste des membres en utilisant la m�thode findAll de doctrine
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
			//on utilise un findBy pour r�cup�rer la liste des utilisateurs on fonction des donn�es de l'utilisateur
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
	
	public function profilAction()
	{
		$result = array();
		$user = $this->getUser();
				
			//si l'utilisateur courant est un admin, on autorise le controleur d'appeler Twig.

		$id_courant = $user->getId();
		//on charge les infos de l'utilisateur courant.
		$manager = $this->getDoctrine()->getManager();
		$data = $manager->getRepository('SiteTrailBundle:Membre')->findOneBy(array('id'=>$id_courant));	

		$result['id'] = $id_courant;
		$result['prenom'] = $data->getPrenom();
		$result['nom'] = $data->getNom();
		$result['email'] = $data->getEmail();
		$result['tel'] = $data->getTelephone();
		$result['date'] = $data->getDatenaissance();
		$result['licence'] = $data->getLicence();		

		$iti = $this->forward('SiteTrailBundle:Itiniraire:getByUser', array('user'  => $id_courant));		
		$result['itineraires'] = json_decode($iti->getContent());	

		//chargement des itinéraires d'un utilisateur donné.
		
		$content = $this->get("templating")->render("SiteTrailBundle:MemberDisplay:ProfilMembre.html.twig",$result);
		return new Response($content);
	}
	
	public function profilSubmitAction(Request $request)
	{
		//on récupère les infos et on les stocke
		$prenom="";$nom="";$email="";$tel="";$date="";$licence="";
		
		$prenom = $request->request->get('Prenom','');
		$nom = $request->request->get('Nom','');
		$email = $request->request->get('Email','');
		$tel = $request->request->get('Tel');
		$date = $request->request->get('Date');
		$licence = $request->request->get('Licence');
		
		//si les variables existent et passent les test, on les compare au infos de l'utilisateur
		
		if(!empty($prenom) && !empty($nom) && !empty($email) && !empty($tel) && !empty($date) && !empty($licence))
		{
			//on récupère l'id de l'utilisateur courant et on insère les valeurs sur son profil.
			$user = $this->getUser();
			$id = $user->getId();
			
			$manager = $this->getDoctrine()->getManager();
			$data = $manager->getRepository('SiteTrailBundle:Membre')->findOneBy(array('id'=>$id)); 
		
			$data->setEmail($email);
			$data->setNom($nom);
			$data->setPrenom($prenom);
			$data->setDatenaissance(new \DateTime(str_replace("/", "-", $date)));
			$data->setTelephone($tel);
			$data->setLicence($licence);
			
			$manager->flush();	
		}

		return $this->redirect($this->generateUrl('site_trail_profilmembre'));
	}
	
	
}


?>