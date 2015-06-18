<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Membre;
use Site\TrailBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use SoapClient;
use Site\TrailBundle\Security\CustomCrypto;
use DateTime;
use Site\TrailBundle\Entity\Image;
/**
 * 
 */
class UserController extends Controller {
    /**
     * 
     * @return View
     */
    public function indexAction() {
		$this->testDeDroits('Administration');

        $content = $this->get("templating")->render("SiteTrailBundle:User:index.html.twig");

        return new Response($content);
    }

    /**
     * Fonction de création d'un utilisateur
     *
     * Cette méthode est appelée en ajax et requiert les paramètres suivants : 
     * 
     * <code>
     * email : Email de l'utilisateur à créer 
     * nom : Nom de l'utilisateur à créer 
     * prenom : Prénom de l'utilisateur à créer 
     * datenaissance : Date de naissance de l'utilisateur à créer 
     * telephone : Téléphone de l'utilisateur à créer 
     * licence : Url du site de la licence de l'utilisateur à créer
     * </code>
     * 
     * @return string 
     *
     * JSON permettant de définir si l'utilisateur a été créé ou non
     *
     * Example en cas de succès :
     * 
     * <code>
     * {
     *     "success": true,
     *     "serverError": false,
     *     "message": "Message"
     * }
     * </code>
     * 
     * Example en cas d'erreur dans la création :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": false,
     *     "message": "Message"
     * }
     * </code>
     * 
     * Example en cas d'erreur du serveur :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": true,
     *     "message": "Message"
     * }
     * </code>
     * 
     * 
     */
    public function createAction() {
        //Seul les roles ayant la permission "administration" auront accés à cette fonction
		$this->testDeDroits('Administration');
		
        //On récupère la requete courrante
        $request = $this->getRequest();
        //On regarde qu'il s'agit bien d'une requête AJAX
        if ($request->isXmlHttpRequest()) {
            try {
                if (!$this->isCsrfTokenValid('default', $request->get('_csrf_token'))) {
                    throw new Exception("CSRF TOKEN ATTAK", 500);
                }
                //On récupère le manager de Doctrine
                $manager = $this->getDoctrine()->getManager();
                //On récupère la variable email qui servira aussi de nom d'utilisateur
                $email = $request->request->get('email');
                //On récupère le nom du membre
                $nom = $request->request->get('nom');
                //On récupère le prénom de l'utilisateur
                $prenom = $request->request->get('prenom');
                //On récupère la date de naissance de l'utilisateur
                $datenaissance = $request->request->get('datenaissance');
                //On récupère le numéro de téléphone de l'utilisateur
                $telephone = $request->request->get('telephone');
                //On associe à l'utilisateur une image de base
                $avatar = $manager->getRepository("SiteTrailBundle:Image")->find(1);
                //On récupère la licence
                $licence = $request->request->get('licence');

                //On récupère le paramètre mot de passe de la requête
                $password = $request->request->get('password');

                //Le role sera celui qu'aura spécifié l'administrateur donc on récupère ce paramètre de la requête
                $role_base = intval($request->request->get('role'));


                //TokenIcs
                $tokenics = md5(uniqid('', true));

                //On fait des vérifications pour voir que les informations saisies sont valide
                //Si l'email est vide
                if ($email == "") {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "L'email ne doit pas être vide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Si l'email n'es pas un email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "L'email n'a pas un format valide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Si l'email n'existe pas déjà dans la base de données
                $userWithEmail = $manager->getRepository('SiteTrailBundle:Membre')->findOneBy(array('email' => $email));

                if (!is_null($userWithEmail)) {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "Cet email est déjà utilisé");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Si le nom est vide
                if ($nom == "") {
                    $nom = null;
                }
                //Si la date de naissance est vide
                if ($datenaissance == "") {
                    $datenaissance = null;
                }
                //Si le prenom est vide
                if ($prenom == "") {
                    $prenom = null;
                }
                //Si le telephone est vide
                if ($telephone == "") {
                    $telephone = null;
                }
                //Si la licence est vide
                if ($licence == "") {
                    $licence = null;
                }
                //Si le mot de passe est vide
                if ($licence != "" && !filter_var($licence, FILTER_VALIDATE_URL)) {
                    $return = array('success' => false, 'serverError' => false, 'message' => "La licence doit être un lien valide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }


                if ($datenaissance != "") {
                    $datenaissance = DateTime::createFromFormat('d/m/Y', $datenaissance);
                    $date_errors = DateTime::getLastErrors();
                    if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
                        $return = array('success' => false, 'serverError' => false, 'message' => "Le format de la date est invalide");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                }

                // On crée l'utilisateur vide
                $user = new Membre();
                //On créé un nouveau role vide
                $role = new Role();
                //On récupère le depot role
                $repository = $manager->getRepository("SiteTrailBundle:Role");
                //On récupère le role spécifié dans la base de données
                $role = $repository->find($role_base);
                //Si le role est null
                if (is_null($role)) {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "Le role spécifié est introuvable");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                $user->setEmail($email);
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setDatenaissance($datenaissance);
                $user->setTelephone($telephone);
                $user->setAvatar($avatar);
                $user->setLicence($licence);
                $user->setTokenics($tokenics);
                $user->setRole($role);
                // On persite l'utilisateur
                //On génère un nouveau mot de passe
                $password = md5(uniqid('', true));



                //Ensuite on essaye de se connecter avec le webservice
                $clientSOAP = new SoapClient(null, array(
                    'uri' => $this->container->getParameter("auth_server_host"),
                    'location' => $this->container->getParameter("auth_server_host"),
                    'trace' => 1,
                    'exceptions' => 1
                ));

                //On appel la méthode du webservice qui permet de se connecter
                $response = $clientSOAP->__call('createUser', array('username' => CustomCrypto::encrypt($email), 'password' => CustomCrypto::encrypt($password), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
                //L'utilisateur n'existe pas dans la base de données ou les identifiants sont incorrects

                if ($response['error'] == true) {
                    $return = array('success' => false, 'serverError' => false, 'message' => $response['message']);
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                $user->setId(CustomCrypto::decrypt($response['id_user']));
                $manager->persist($user);

                //On déclenche l'enregistrement dans la base de données
                $manager->flush();

                //On envoie le mot de passe généré à l'utilisateur
                $message = \Swift_Message::newInstance()
                        ->setSubject('Création de compte ')
                        ->setFrom('noreply.trail@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody("Vos identifiants pour vous connecter : \n login = " . $user->getEmail() . "\n mot de passe = " . $password);
                $this->get('mailer')->send($message);


                //Tout s'est déroulé correctement
                $return = array('success' => true, 'serverError' => false, 'message' => "L'utilisateur est inscrit");
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            } catch (Exception $e) {
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

     /**
     * Fonction de chargement des roles
     *
     * Cette méthode est appelée en ajax et ne requiert aucuns paramètres 
     * 
     * @return string 
     *
     * JSON contenant la liste des roles de la base de données
     *
     * Example en cas de succès :
     * 
     * <code>
     * {
     *     "success": true,
     *     "serverError": false,
     *     "roles": role
     * }
     * </code>
     * 
     * Example en cas d'erreur dans la création :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": false,
     *     "message": "Message"
     * }
     * </code>
     * 
     * Example en cas d'erreur du serveur :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": true,
     *     "message": "Message"
     * }
     * </code>
     * 
     * 
     */
    public function loadRolesAction() {
		$this->testDeDroits('Administration');
		
        //On récupère la requête courrante
        $request = $this->getRequest();
        //On regarde qu'il s'agit bien d'une requête ajax
        if ($request->isXmlHttpRequest()) {
            try {
				//On récupère le manager de Doctrine
				$manager = $this->getDoctrine()->getManager();
				//On récupère le dépôt role
				$repository = $manager->getRepository("SiteTrailBundle:Role");
				//On récupère tous les rôles
				$roles = $repository->findAll();
				$return = array('success' => true, 'serverError' => false, 'roles' => $roles);
				$response = new Response(json_encode($return));
				$response->headers->set('Content-Type', 'application/json');
				return $response;
            } catch (Exception $e) {
                //Il y a une erreur côté serveur
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

         /**
     * Fonction de récupération de l'état de l'utilisateur (activé / désactivé)
     *
     * Cette méthode est appelée en ajax et requiert les paramètres suivants : 
     * 
     * <code>
     * id_user : id de l'utilisateur
     * </code>
     * 
     * @return string 
     *
     * JSON contenant l'état d'activation de l'utilisateur
     *
     * Example en cas de succès :
     * 
     * <code>
     * {
     *     "success": true,
     *     "serverError": false,
     *     "actif": int
     * }
     * </code>
     * 
     * Example en cas d'erreur dans la création :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": false,
     *     "message": "Message"
     * }
     * </code>
     * 
     * Example en cas d'erreur du serveur :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": true,
     *     "message": "Message"
     * }
     * </code>
     * 
     * 
     */
    public function getUserActivationAction() {
		$this->testDeDroits('Administration');
		
        //Permet de récupérer dans le webservice si l'utilisateur passé en paramètre existe ou non
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try {
				//On récupère l'id de l'utilisateur que l'on souhaite rechercher
				$id = $request->request->get('id_user');
				//On récupère le manager de doctrine
				$clientSOAP = new SoapClient(null, array(
					'uri' => $this->container->getParameter("auth_server_host"),
					'location' => $this->container->getParameter("auth_server_host"),
					'trace' => 1,
					'exceptions' => 1
				));

				//On appel la méthode du webservice qui permet de se connecter
				$response = $clientSOAP->__call('getUserActivation', array('id' => CustomCrypto::encrypt($id), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
				//Si il y a une erreur

				if ($response['error'] == true) {
					$return = array('success' => false, 'serverError' => false, 'message' => $response['message']);
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}
				$return = array('success' => true, 'serverError' => false, 'actif' => $response['actif']);
				$response = new Response(json_encode($return));
				$response->headers->set('Content-Type', 'application/json');
				return $response;
            } catch (Exception $e) {
                //Il y a eu une erreur côté serveur
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

    //Récupération de la liste des utilisateurs
    public function getAllUsersAction() {
		$this->testDeDroits('Annuaire');
		
        //On récupère la requête courrante
        $request = $this->getRequest();
        //On regarde qu'il s'agit bien d'une requête ajax
        if ($request->isXmlHttpRequest()) {
            try {
				//On récupère le manager de Doctrine
				$manager = $this->getDoctrine()->getManager();
				//On récupère le dépôt utilisateur
				$repository = $manager->getRepository("SiteTrailBundle:Membre");
				//On récupère tous les utilisateurs
				$users = $repository->findAll();
				$actifs = array();
				foreach ($users as $value) {
					$clientSOAP = new SoapClient(null, array(
						'uri' => $this->container->getParameter("auth_server_host"),
						'location' => $this->container->getParameter("auth_server_host"),
						'trace' => 1,
						'exceptions' => 1
					));

					//On appel la méthode du webservice qui permet de se connecter
					$response = $clientSOAP->__call('getUserActivation', array('id' => CustomCrypto::encrypt($value->getId()), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
					//Si il y a une erreur

					if ($response['error'] == true) {
						$return = array('success' => false, 'serverError' => false, 'message' => $response['message']);
						$response = new Response(json_encode($return));
						$response->headers->set('Content-Type', 'application/json');
						return $response;
					}
					$actifs[] = $response['actif'];
				}
				$visibilite = $this->get('security.context')->isGranted('ROLE_Administrateur');
				$return = array('success' => true, 'serverError' => false, 'users' => $users, 'visibilite' => $visibilite, 'actif' => $actifs);
				$response = new Response(json_encode($return));
				$response->headers->set('Content-Type', 'application/json');
				return $response;
            } catch (Exception $e) {
                //Il y a une erreur côté serveur
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

    /**
     * Fonction d'activation ou de désactivation de l'utilisateur
     *
     * Cette méthode est appelée en ajax et requiert les paramètres suivants : 
     * 
     * <code>
     * id_user : id de l'utilisateur
     * activation: int en fonction de l'état que l'on veut donner à l'utilisateur
     * </code>
     * 
     * @return string 
     *
     * JSON contenant le succès de l'opération
     *
     * Example en cas de succès :
     * 
     * <code>
     * {
     *     "success": true,
     *     "serverError": false,
     *     "message": "message"
     * }
     * </code>
     * 
     * Example en cas d'erreur dans la création :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": false,
     *     "message": "Message"
     * }
     * </code>
     * 
     * Example en cas d'erreur du serveur :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": true,
     *     "message": "Message"
     * }
     * </code>
     * 
     * 
     */
    public function deleteAction() {
        $this->testDeDroits('Administration');
		
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try {
				$id = $request->request->get('id_user');
				$activation = $request->request->get('activation');
				$manager = $this->getDoctrine()->getManager();    //On récupère le manager de doctrine
				$repository = $manager->getRepository("SiteTrailBundle:Membre");
				$usertodelete = $repository->find($id);
				if (is_null($usertodelete)) {
					$return = array('success' => false, 'serverError' => false, 'message' => "L'utilisateur spécifié n'existe plus dans la base de données");
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}

				//On désactive l'utilisateur sur le service d'authentification
				//Ensuite on essaye de se connecter avec le webservice
				$clientSOAP = new SoapClient(null, array(
					'uri' => $this->container->getParameter("auth_server_host"),
					'location' => $this->container->getParameter("auth_server_host"),
					'trace' => 1,
					'exceptions' => 1
				));

				//On appel la méthode du webservice qui permet de modifier l'état de l'utilisateur
				$response = $clientSOAP->__call('updateUserActivation', array('id' => CustomCrypto::encrypt($usertodelete->getId()), 'activation' => CustomCrypto::encrypt($activation), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
				//L'utilisateur n'existe pas dans la base de données du serveur d'authentification

				if ($response['error'] == true) {
					$return = array('success' => false, 'serverError' => false, 'message' => $response['message']);
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}
				//L'utilisateur a bien été supprimé
                if($activation == 0)
                {
                    $messagea = "L'utilisateur a bien été désactivé";
                }
                else
                {
                     $messagea = "L'utilisateur a bien été activé";
                }
				$return = array('success' => true, 'serverError' => false, 'message' => $messagea);
				$response = new Response(json_encode($return));
				$response->headers->set('Content-Type', 'application/json');
				return $response;
            } catch (Exception $e) {
                //Il y a une erreur côté serveur
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

    /**
     * Fonction de récupération des informations d'un utilisateur dans la base de données
     *
     * Cette méthode est appelée en ajax et requiert les paramètres suivants : 
     * 
     * <code>
     * id_user : id de l'utilisateur
     * </code>
     * 
     * @return string 
     *
     * JSON contenant les informations de l'utilisateur
     *
     * Example en cas de succès :
     * 
     * <code>
     * {
     *     "success": true,
     *     "serverError": false,
     *     "user": Objet membre sérailisé
     *     "role": Objet role de l'utilisateur sérialisé
     * }
     * </code>
     * 
     * Example en cas d'erreur dans la création :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": false,
     *     "message": "Message"
     * }
     * </code>
     * 
     * Example en cas d'erreur du serveur :
     * 
     * <code>
     * {
     *     "success": false,
     *     "serverError": true,
     *     "message": "Message"
     * }
     * </code>
     * 
     * 
     */
    public function getUserAction() {
		$this->testDeDroits('Administration');
		
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try {
				//On récupère l'id de l'utilisateur que l'on souhaite rechercher
				$id = $request->request->get('id_user');
				//On récupère le manager de doctrine
				$manager = $this->getDoctrine()->getManager();
				$repository = $manager->getRepository("SiteTrailBundle:Membre");
				//On récupère l'utilisateur à l'aide de l'id passé en paramètre à la requête
				$user = $repository->find($id);
				//Si l'utilisateur n'existe plus dans la base de données
				if (is_null($user)) {
					$return = array('success' => false, 'serverError' => false, 'message' => "L'utilisateur spécifié n'existe plus dans la base de données");
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}
				//On récupère l'id du role de l'utilisateur
				$repository = $manager->getRepository("SiteTrailBundle:Role");
				$role = $repository->find($user->getRole()->getId());
				$return = array('success' => true, 'serverError' => false, 'user' => $user, 'role' => $role);
				$response = new Response(json_encode($return));
				$response->headers->set('Content-Type', 'application/json');
				return $response;
            } catch (Exception $e) {
                //Il y a eu une erreur côté serveur
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

    //Mise à jour d'un utilisateur dans la base de données
    public function updateUserAction() {
		$this->testDeDroits('Administration');
		
        $request = $this->getRequest();  //On récupère la requete courrante
        if ($request->isXmlHttpRequest()) {    //On regarde qu'il s'agit bien d'une requête AJAX
            try {
				//On récupère l'identifiant de l'utilisateur à mettre à jour
				$userToUpdate = intval($request->request->get('id_user'));
				//On récupère son email
				$email = $request->request->get('emailUpdate');
				//On récupère son prenom
				$nom = $request->request->get('nomUpdate');
				//On récupère son prenom
				$prenom = $request->request->get('prenomUpdate');
				//On récupère sa date de naissance
				$datenaissance = $request->request->get('datenaissanceUpdate');
				//On récupère son telephone
				$telephone = $request->request->get('telephoneUpdate');
				//On récupère son role
				$roleupdate = intval($request->request->get('roleUpdate'));


				//On récupère le manager de Doctrine
				$manager = $this->getDoctrine()->getManager();
				//On récupère le depot role
				$repository = $manager->getRepository("SiteTrailBundle:Role");

				// On récupère l'utilisateur a mettre a jour
				$user = $manager->getRepository("SiteTrailBundle:Membre")->find($userToUpdate);
				//Si l'utilisateur n'existe plus dans la base de données
				if (is_null($user)) {
					$return = array('success' => false, 'serverError' => false, 'message' => "L'utilisateur spécifié n'existe plus dans la base de données");
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}
				//On récupère le role spécifié pour la mise à jour dans la base de données
				$role = $repository->find($roleupdate);
				if (is_null($role)) {
					$return = array('success' => false, 'serverError' => false, 'message' => "Le rôle spécifié n'existe plus dans la base de données");
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}

				//On fait des vérifications pour voir que les informations saisies sont valide
				//Si l'email est vide
				if ($email == "") {
					//success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
					$return = array('success' => false, 'serverError' => false, 'message' => "L'email ne doit pas être vide");
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}
				//Si l'email n'es pas un email
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					//success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
					$return = array('success' => false, 'serverError' => false, 'message' => "L'email n'a pas un format valide");
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}
				//Si l'email n'existe pas déjà dans la base de données (pour un utilisateur différent de celui que l'on met à jour
				$userWithEmail = $manager->getRepository('SiteTrailBundle:Membre')->findOneBy(array('email' => $email));

				if (!is_null($userWithEmail) && $userWithEmail->getId() != $user->getId()) {
					//success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
					$return = array('success' => false, 'serverError' => false, 'message' => "Cet email est déjà utilisé");
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}


				//Si le role est null
				if (is_null($role)) {
					//success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
					$return = array('success' => false, 'serverError' => false, 'message' => "Le role spécifié est introuvable");
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}


				//Si le nom est vide
				if ($nom == "") {
					$nom = null;
				}
				//Si la date de naissance est vide
				if ($datenaissance == "") {
					$datenaissance = null;
				}
				//Si le prenom est vide
				if ($prenom == "") {
					$prenom = null;
				}
				//Si le telephone est vide
				if ($telephone == "") {
					$telephone = null;
				}

				if ($datenaissance != null) {
					$datenaissance = DateTime::createFromFormat('d/m/Y', $datenaissance);
					$date_errors = DateTime::getLastErrors();
					if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
						$return = array('success' => false, 'serverError' => false, 'message' => "Le format de la date est invalide");
						$response = new Response(json_encode($return));
						$response->headers->set('Content-Type', 'application/json');
						return $response;
					}
				}


				$user->setEmail($email);
				$user->setNom($nom);
				$user->setPrenom($prenom);
				$user->setDatenaissance($datenaissance);
				$user->setTelephone($telephone);


				// On définit le rôle de l'utilisateur (récupéré dans la base de donnée)
				$user->setRole($role);

				//On déclenche l'enregistrement dans la base de données
				$manager->flush();

				//On ajoute les modifications dans le serveur d'authentification (juste l'email)
				$clientSOAP = new SoapClient(null, array(
					'uri' => $this->container->getParameter("auth_server_host"),
					'location' => $this->container->getParameter("auth_server_host"),
					'trace' => 1,
					'exceptions' => 1
				));

				//On appel la méthode du webservice qui permet de modifier l'état de l'utilisateur
				$response = $clientSOAP->__call('updateUser', array('id' => CustomCrypto::encrypt($user->getId()), 'email' => CustomCrypto::encrypt($user->getEmail()), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
				//L'utilisateur n'existe pas dans la base de données du serveur d'authentification

				if ($response['error'] == true) {
					$return = array('success' => false, 'serverError' => false, 'message' => $response['message']);
					$response = new Response(json_encode($return));
					$response->headers->set('Content-Type', 'application/json');
					return $response;
				}

				//Tout s'est déroulé correctement
				$return = array('success' => true, 'serverError' => false, 'message' => "L'utilisateur a été mis à jour");
				$response = new Response(json_encode($return));
				$response->headers->set('Content-Type', 'application/json');
				return $response;
					
            } catch (Exception $e) {
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

    //Permettra de connecter un utilisateur
    public function logInAction() {
        $request = $this->getRequest();
        //On regarde qu'il s'agit bien d'une requête ajax
        if ($request->isXmlHttpRequest()) {
            try {
                if (!$this->isCsrfTokenValid('default', $request->get('_csrf_token'))) {
                    throw new Exception("CSRF TOKEN ATTAK MAGGLE", 500);
                }
                //On récupère l'email
                $email = $request->request->get('_email');
                //On récupère son mot de passe
                $password = $request->request->get('_password');
                if ($email == "" || $password == "") {
                    $return = array('success' => false, 'serverError' => false, 'message' => "Le nom d'utilisateur ou le mot de passe ne doivent pas être vide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Ensuite on essaye de se connecter avec le webservice
                $clientSOAP = new SoapClient(null, array(
                    'uri' => $this->container->getParameter("auth_server_host"),
                    'location' => $this->container->getParameter("auth_server_host"),
                    'trace' => true,
                    'exceptions' => true
                ));

                //On appel la méthode du webservice qui permet de se connecter
                $response = $clientSOAP->__call('logUserIn', array('username' => CustomCrypto::encrypt($email), 'password' => CustomCrypto::encrypt($password), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
                //L'utilisateur n'existe pas dans la base de données ou les identifiants sont incorrects

                if ($response['connected'] == false) {
                    $return = array('success' => false, 'serverError' => false, 'message' => $response['message']);
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Le webservice possède bien un compte d'utilisateur pour les informations saisies.
                //Il faut donc vérifier si l'utilisateur existe dans la base de données de ce site
                $manager = $this->getDoctrine()->getManager();

                // On récupère le membre dans la base de données si il existe
                $userid = CustomCrypto::decrypt($response['userid']);
                $membre = $manager->getRepository("SiteTrailBundle:Membre")->find($userid);

                //Si l'utilisateur n'existe pas dans notre base de données
                if (is_null($membre)) {
                    $return = array('success' => false, 'serverError' => false, 'message' => "Existe pas dans la bdd");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }

                //L'utilisateur existe dans notre base de données et il est connecté, on créé le cookie d'authentification
                setcookie($this->container->getParameter("auth_cookie"), CustomCrypto::encrypt($membre->getId() . "/" . $membre->getEmail() . "/" . $membre->getRole()->getLabel()), 0, '/');

                $return = array('success' => true, 'serverError' => false);
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            } catch (Exception $e) {
                //Il y a une erreur côté serveur
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

    public function logOutAction() {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();
        $response = new RedirectResponse($this->generateUrl('site_trail_homepage_empty'));
        $response->headers->clearCookie($this->container->getParameter("auth_cookie"));

        return $response;
    }

    //Permet de changer le mot de passe d'un utilisateur
    public function changePasswordAction() {
        $request = $this->getRequest();
        //On regarde qu'il s'agit bien d'une requête ajax
        if ($request->isXmlHttpRequest()) {
            try {
                if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
                    //On récupère l'ancien mot de passe
                    $oldpassword = $request->request->get('oldpassword');
                    //On récupère le nouveau mot de passe
                    $newpassword = $request->request->get('newpassword');
                    //On récupère l'email de l'utilisateur actuel
                    $email = $this->getUser()->getEmail();
                    //On récupère son id
                    $id = $this->getUser()->getId();

                    if ($oldpassword == "" || $newpassword == "") {
                        $return = array('success' => false, 'serverError' => false, 'message' => "Veuillez remplir le formulaire");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                    //Ensuite on essaye de se connecter avec le webservice
                    $clientSOAP = new SoapClient(null, array(
                        'uri' => $this->container->getParameter("auth_server_host"),
                        'location' => $this->container->getParameter("auth_server_host"),
                        'trace' => true,
                        'exceptions' => true
                    ));

                    //On appel la méthode du webservice qui permet de se connecter
                    $response = $clientSOAP->__call('logUserIn', array('username' => CustomCrypto::encrypt($email), 'password' => CustomCrypto::encrypt($oldpassword), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
                    //L'utilisateur n'existe pas dans la base de données ou les identifiants sont incorrects

                    if ($response['connected'] == false) {
                        $return = array('success' => false, 'serverError' => false, 'message' => "Le mot de passe renseigné est invalide");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                    //Le webservice possède bien un compte d'utilisateur pour les informations saisies.
                    //Maintenant il faut changer le mot de passe

                    $clientSOAP = new SoapClient(null, array(
                        'uri' => $this->container->getParameter("auth_server_host"),
                        'location' => $this->container->getParameter("auth_server_host"),
                        'trace' => 1,
                        'exceptions' => 1
                    ));

                    //On appel la méthode du webservice qui permet de modifier l'état de l'utilisateur
                    $response = $clientSOAP->__call('changePassword', array('id' => CustomCrypto::encrypt($id), 'newpassword' => CustomCrypto::encrypt($newpassword), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
                    //L'utilisateur n'existe pas dans la base de données du serveur d'authentification

                    if ($response['error'] == true) {
                        $return = array('success' => false, 'serverError' => false, 'message' => $response['message']);
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }

                    $return = array('success' => true, 'serverError' => false);
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
            } catch (Exception $e) {
                //Il y a une erreur côté serveur
                $return = array('success' => false, 'serverError' => true, 'message' => $e->getMessage());
                $response = new Response(json_encode($return));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        } else {
            //La requête n'es pas une requête ajax, on envoie une erreur
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }

    //Affichage du formulaire d'ajout d'un utilisateur
    //Affichage de la liste des membres
    public function annuaireAction() {
		$this->testDeDroits('Annuaire');
		
        $content = $this->get("templating")->render("SiteTrailBundle:User:annuaire.html.twig");

        return new Response($content);
    }

    public function uploadAvatarAction() {
		$this->testDeDroits('Administration');
		
        //Sauvegarde du fichier   
        $target_dir = $this->container->getParameter("upload_directory");
        $target_file = $target_dir . basename($_FILES["fichier"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fichier"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "Le fichier n'est pas une image.";
                $uploadOk = 0;
            }
        }

        //On vérifie la taille du fichier
        if ($_FILES["fichier"]["size"] > 5000000) {
            echo "L'image est trop volomineuse.";
            $uploadOk = 0;
        }

        //Autorisation de certaines extensions de fichier
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Seules les extensions JPG, JPEG, PNG & GIF sont autorisées.";
            $uploadOk = 0;
        }

        //On vérifie qu'il n'y a pas eu d'erreurs lors de l'upload
        if ($uploadOk == 0) {
            echo "Il y a eu un problème lors de l'envoi du fichier.";
        } else {
            $date = new \DateTime;
            $fileName = "image" . date_format($date, 'U') . "." . $imageFileType;
            $newFile = $target_dir.$fileName;
           
            if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $newFile)) {
                list($width, $height, $type, $attr) = getimagesize($newFile);

                $manager = $this->getDoctrine()->getManager();

                //On rajoute l'image dans la base de données                
                $titre = "Avatar";
                $description = "avatar";
                $poids = $_FILES["fichier"]["size"];
                $taille = $width . 'x' .$height;
                $auteur = $this->getUser();
                $repository = $manager->getRepository("SiteTrailBundle:Categorie");

                $categorie = $repository->find(1);
                $repository = $manager->getRepository("SiteTrailBundle:Image");
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
                
                $user = $manager->getRepository("SiteTrailBundle:Membre")->find($this->getUser());
                $user->setAvatar($manager->getRepository("SiteTrailBundle:Image")->find($newImage->getId()));
                $manager->flush();
                $response = new RedirectResponse($this->generateUrl('site_trail_profilmembre'));
                return $response;
            } else {
                return new RedirectResponse($this->generateUrl('site_trail_profilmembre'));
            }
        }
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
