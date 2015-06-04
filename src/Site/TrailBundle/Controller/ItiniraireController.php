<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Itineraire;
use Site\TrailBundle\Entity\DifficulteParcours;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ItiniraireController extends Controller 
{
	public function listAction()
	{
		$this->testDeDroits('Itinéraires');
		
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
		$this->testDeDroits('Itinéraires');
		
		$clientSOAP = new \SoapClient(null, array(
                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

		//Chargement de la liste des difficultés dans le select
        $responseDiff = $clientSOAP->__call('difficultelist',array());

        //Chargement de la liste des status dans le select
        $responseStat = $clientSOAP->__call('statuslist',array());

        //Chargement de la liste des types de chemin dans le select
        $responseType = $clientSOAP->__call('typecheminlist',array());
		
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
		$this->testDeDroits('Itinéraires');
		
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
			$params["public"] = $request->request->get("public");
			

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
		$this->testDeDroits('Itinéraires');
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
                        
            //Récupération du service
            $evenementService = $this->container->get('evenement_service');
            $eventService = $evenementService->getEventAndEventUsed($id);
            $listEvent = $eventService['allEvent'];
            $selectedEvent = $eventService['usedEvent'];            
            
            $content = $this->get("templating")->render("SiteTrailBundle:Itiniraire:FicheItineraire.html.twig",
                                                        array("resultats" => $res,
                                                                "jsonObject" => $response,
                                                                "listEvent" => $listEvent,
                                                                "usedEvent" => $selectedEvent));
            return new Response($content);
	}

	public function getByUserAction($user)
	{
		$this->testDeDroits('Itinéraires');
		
		$clientSOAP = new \SoapClient(null, array(
                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
                    'trace' => true,
                    'exceptions' => true
                ));

                //On appel la méthode du webservice qui permet de se connecter
                $response = $clientSOAP->__call('getByUser', array("user" => $user));
    
		$res = json_decode($response);
		return new Response($response);
	}

	public function updateAction(Request $request)
    {
		$this->testDeDroits('Itinéraires');
		
      if ($request->isXMLHttpRequest()) 
      {
      	//Appel du service de sauvegarde
        	$params = array();		
			$params["nom"] = $request->request->get("nom");
			$params["typechemin"] = $request->request->get("typechemin");
			$params["difficulte"] = $request->request->get("difficulte");
			$params["description"] = $request->request->get("description");
			$params["numero"] = $request->request->get("numero");
			$params["auteur"] = $request->request->get("auteur");
			$params["status"] = $request->request->get("status");
			$params["public"] = $request->request->get("public");
			$params["id"] = $request->request->get("id");
			//var_dump($params);
			

			$clientSOAP = new \SoapClient(null, array(
	                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'trace' => true,
	                    'exceptions' => true
	                ));

	        $response = $clientSOAP->__call('update', $params);
	        $res = json_decode($response);
                
            //Ajout des evenementItineraire
            $evenementService = $this->container->get('evenement_service');
           // var_dump($request->request->get("evenement"));
           // var_dump($request->request->get("id"));
           // var_dump($request->request->get("nom"));
            $evenementService->updateParcours($request->request->get("evenement"), $request->request->get("id"));
            
	    return new Response(json_encode(array("result" => "success","code" => 200)));      
      }
      return new Response('This is not ajax!', 400);
    }

    public function deleteAction(Request $request)
    {
		$this->testDeDroits('Itinéraires');
		
      if ($request->isXMLHttpRequest()) 
      {
      	//Appel du service de sauvegarde
        	$params = array();		
			$params["id"] = $request->request->get("id");

			$clientSOAP = new \SoapClient(null, array(
	                    'uri' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'location' => "http://localhost/Carto/web/app_dev.php/itineraire",
	                    'trace' => true,
	                    'exceptions' => true
	                ));

	        $response = $clientSOAP->__call('delete', $params);
	        $res = json_decode($response);
	        return new Response(json_encode(array("result" => "success","code" => 200)));      
      }
      return new Response('This is not ajax!', 400);
    }

    public function getFormDataAction(Request $request)
    {
		$this->testDeDroits('Itinéraires');
		
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

        $resDiff = json_decode($responseDiff);
		$resStat = json_decode($responseStat);
		$resType = json_decode($responseType);
		return new JsonResponse(array("diffs" => $resDiff,"stats" => $resStat,"typechemin" => $resType));
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


?>