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
                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

                //On appel la méthode du webservice qui permet de se connecter
                $response = $clientSOAP->__call('itilist', array());
    
		$res = json_decode($response);
		$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:ItineraireDisplay.html.twig",array("resultats" => $res));
		return new Response($content);
	}
	
	public function searchAction(Request $request)
	{
		//Chargement de la liste des difficultés dans le select
		$clientSOAPDiff = new \SoapClient(null, array(
                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

        $responseDiff = $clientSOAPDiff->__call('difficultelist',array());

        //Chargement de la liste des status dans le select
        $clientSOAPStat = new \SoapClient(null, array(
                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

        $responseStat = $clientSOAPDiff->__call('statuslist',array());

        //Chargement de la liste des types de chemin dans le select
        $clientSOAPType = new \SoapClient(null, array(
                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

        $responseType = $clientSOAPDiff->__call('typecheminlist',array());
		
        if($request->request->get("valid") == "ok")
        {
        	//Appel du service de recherche
        	$search = array();		
			$search["nom"] = $request->request->get("nom");
			$search["typechemin"] = $request->request->get("typechemin");
			$search["longueur"] = $request->request->get("longueur");
			$search["datecrea"] = $request->request->get("datecrea");
			$search["difficulte"] = $request->request->get("difficulte");
			$search["status"] = $request->request->get("status");
			

			$clientSOAP = new \SoapClient(null, array(
	                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'trace' => true,
	                    'exceptions' => true
	                ));

	        $response = $clientSOAP->__call('search', $search);

			$res_search = json_decode($response);
			$resDiff = json_decode($responseDiff);
			$resStat = json_decode($responseStat);
			$resType = json_decode($responseType);
			$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:SearchItineraire.html.twig",array("resultats" => $res_search,"diffs" => $resDiff,"stats" => $resStat,"typechemin" => $resType, "list" => array()));
        }
		else
		{
			// Recupère la liste complète
			$clientSOAP = new \SoapClient(null, array(
						'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
						'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
						'trace' => true,
						'exceptions' => true
					));

			//On appel la méthode du webservice qui permet de se connecter
			$response = $clientSOAP->__call('itilist', array());
		
			$res_list = json_decode($response);
			$resDiff = json_decode($responseDiff);
			$resStat = json_decode($responseStat);
			$resType = json_decode($responseType);
			$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:SearchItineraire.html.twig",array("resultats" => array(),"diffs" => $resDiff,"stats" => $resStat,"typechemin" => $resType,"list" => $res_list));
		}

		return new Response($content);
	}

	public function saveRouteAction(Request $request)
    {
      if ($request->isXMLHttpRequest()) 
      {
      	$clientSOAPDiff = new \SoapClient(null, array(
                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
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
			$params["points"] = $request->request->get("points");
			

			$clientSOAP = new \SoapClient(null, array(
	                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'trace' => true,
	                    'exceptions' => true
	                ));

	        $response = $clientSOAP->__call('save', $params);
	        $res = json_decode($response);
	        return new JsonResponse(array('data' => $response["result"]),$response["code"]);      
      }
      return new Response('This is not ajax!', 400);
    }


	public function getByIdAction($id)
	{
        	//Appel du service de recherche
        	$search = array();		
			$search["id"] = $id;
			
			$clientSOAP = new \SoapClient(null, array(
	                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'trace' => true,
	                    'exceptions' => true
	                ));

	        $response = $clientSOAP->__call('getById', $search);

			$res = json_decode($response);
			
			$content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:FicheItineraire.html.twig",array("resultats" => $res,"jsonObject" => $response));
			return new Response($content);
	}

}


?>