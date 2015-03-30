<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Itineraire;
use Site\TrailBundle\Entity\DifficulteParcours;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ItiniraireController extends Controller 
{
	public function listAction()
	{
		$clientSOAP = new \SoapClient(null, array(
                    'uri' => "http://localhost/carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

                //On appel la méthode du webservice qui permet de se connecter
                $response = $clientSOAP->__call('itilist', array());
        //return new Response($response);
    
		$res = json_decode($response);
		$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:ItineraireDisplay.html.twig",array("resultats" => $res));
		return new Response($content);
	}
	
	public function searchAction(Request $request)
	{
		//Chargement de la liste des difficultés dans le select
		$clientSOAPDiff = new \SoapClient(null, array(
                    'uri' => "http://localhost/carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

        $responseDiff = $clientSOAPDiff->__call('difficultelist',array());

        if($request->request->get("valid") == "ok")
        {
        	//Appel du service de recherche
        	$search = array();		
			$search["nom"] = $request->request->get("nom");
			$search["typechemin"] = $request->request->get("typechemin");
			$search["denivelep"] = $request->request->get("denivelep");
			$search["denivelen"] = $request->request->get("denivelen");
			$search["datecrea"] = $request->request->get("datecrea");
			$search["difficulte"] = $request->request->get("difficulte");
			

			$clientSOAP = new \SoapClient(null, array(
	                    'uri' => "http://localhost/carto/web/app_dev.php/itineraire",
	                    'location' => "http://localhost/carto/web/app_dev.php/itineraire",
	                    'trace' => true,
	                    'exceptions' => true
	                ));

	        $response = $clientSOAP->__call('search', $search);

			$res = json_decode($response);
			$resDiff = json_decode($responseDiff);
			
			$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:SearchItineraire.html.twig",array("resultats" => $res,"diffs" => $resDiff));
			return new Response($content);
        }

		$resDiff = json_decode($responseDiff);
		$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:SearchItineraire.html.twig",array("resultats" => array(),"diffs" => $resDiff));
		return new Response($content);
	}

	public function saveRouteAction(Request $request)
    {
      if ($request->isXMLHttpRequest()) 
      {
      	$clientSOAPDiff = new \SoapClient(null, array(
                    'uri' => "http://localhost/carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

      	//Appel du service de sauvegarde
        	$params = array();		
			$params["nom"] = $request->request->get("nom");
			$params["typechemin"] = $request->request->get("typechemin");
			$params["denivelep"] = $request->request->get("denivelep");
			$params["denivelen"] = $request->request->get("denivelen");
			$params["datecrea"] = new \DateTime('now');
			$params["difficulte"] = $request->request->get("difficulte");
			$params["longueur"] = $request->request->get("longueur");
			$params["description"] = $request->request->get("description");
			$params["numero"] = $request->request->get("numero");
			$params["auteur"] = $request->request->get("auteur");
			$params["status"] = $request->request->get("status");
			

			$clientSOAP = new \SoapClient(null, array(
	                    'uri' => "http://localhost/carto/web/app_dev.php/itineraire",
	                    'location' => "http://localhost/carto/web/app_dev.php/itineraire",
	                    'trace' => true,
	                    'exceptions' => true
	                ));

	        $response = $clientSOAP->__call('save', $params);
	        $res = json_decode($response);
	        return new JsonResponse(array('data' => $response["result"]),$response["code"]);      
      }
      return new Response('This is not ajax!', 400);
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
					->getRepository('SiteTrailBundle:Itineraire')
				;

				$listItiniraire['id'] = $repository->findBy(array('id' => $search));
				
				//$listItiniraire['typechemin'] = $repository->findBy(array('typechemin' => $search));

			}

		$content = $this->get("templating")->render("SiteTrailBundle:Itineraire:ItiniraireDisplay.html.twig",array("resultats" => $listItiniraire));
		return new Response($content);
	}

}


?>