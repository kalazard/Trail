<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\Utilisateur;
use Site\TrailBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use SoapClient;
use Site\TrailBundle\Security\CustomCrypto;

class UserController extends Controller {

    public function indexAction() {

        $content = $this->get("templating")->render("SiteTrailBundle:User:index.html.twig");


        return new Response($content);
    }

    //Création d'un utilisateur
    public function createAction() {
        //On récupère la requete courrante
        $request = $this->getRequest();
        //On regarde qu'il s'agit bien d'une requête AJAX
        if ($request->isXmlHttpRequest()) {
            try {
                //On récupère la variable email qui servira aussi de nom d'utilisateur
                $email = $request->request->get('email');
                //On récupère le paramètre mot de passe de la requête
                $password = $request->request->get('password');
                //Le role de base lors de l'inscription est le role "ROLE_Utilisateur" 
                $role_base = 2;
                //Si l'utilisateur qui créé un utilisateur est administrateur, il spécifie forcément le role
                if ($this->get('security.context')->isGranted('ROLE_Administrateur')) {
                    //Le role sera celui qu'aura spécifié l'administrateur donc on récupère ce paramètre de la requête
                    $role_base = intval($request->request->get('role'));
                }
                //On récupère le manager de Doctrine
                $manager = $this->getDoctrine()->getManager();
                //On récupère le depot role
                $repository = $manager->getRepository("SiteTrailBundle:Role");

                // On crée l'utilisateur vide
                $user = new Utilisateur();
                //On créé un nouveau role vide
                $role = new Role();
                //On récupère le role spécifié dans la base de données
                $role = $repository->find($role_base);

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
                $userWithEmail = $manager->getRepository('SiteTrailBundle:Utilisateur')->findOneBy(array('email' => $email));

                if (!is_null($userWithEmail)) {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "Cet email est déjà utilisé");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Si le mot de passe est vide
                if ($password == "") {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "Le mot de passe ne doît pas être vide");
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

                //On récupère l'encoder factory du fichier security.yml (ici sha512)
                $factory = $this->get('security.encoder_factory');
                //On set l'email et le mot de passe
                $user->setEmail($email);
                //On génère une nouvelle clé de salage en md5
                $user->setSalt(md5(uniqid('', true)));
                $encoder = $factory->getEncoder($user);
                //On crypte le mot de passe avec la clé de salage
                $password = $encoder->encodePassword($password, $user->getSalt());
                $user->setPassword($password);    //On indique le mot de passe
                // On définit le rôle de l'utilisateur (récupéré dans la base de donnée)
                $user->setRoles($role);
                //On ajoute le token pour le calendrier ICS
                $user->setTokenics(md5(uniqid('', true)));
                // On persite l'utilisateur
                $manager->persist($user);

                //On déclenche l'enregistrement dans la base de données
                $manager->flush();
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

    //Permttra de charger la liste des rôles disponibles
    public function loadRolesAction() {
        //On récupère la requête courrante
        $request = $this->getRequest();
        //On regarde qu'il s'agit bien d'une requête ajax
        if ($request->isXmlHttpRequest()) {
            try {
                //On vérifie que l'utilisateur courant est boen administrateur
                if ($this->get('security.context')->isGranted('ROLE_Administrateur')) {
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
                } else {
                    //L'utilisateur actuellement connecté n'es pas adminstrateur, on ne renvoie donc rien
                    $return = array('success' => false, 'serverError' => false);
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

    //Récupération de la liste des utilisateurs
    public function getAllUsersAction() {

        //On récupère la requête courrante
        $request = $this->getRequest();
        //On regarde qu'il s'agit bien d'une requête ajax
        if ($request->isXmlHttpRequest()) {
            try {
                //On vérifie que l'utilisateur courant est bien administrateur ou membre
                if ($this->get('security.context')->isGranted('ROLE_Administrateur') || $this->get('security.context')->isGranted('ROLE_Membre')) {
                    //On récupère le manager de Doctrine
                    $manager = $this->getDoctrine()->getManager();
                    //On récupère le dépôt utilisateur
                    $repository = $manager->getRepository("SiteTrailBundle:Utilisateur");
                    //On récupère tous les utilisateurs
                    $users = $repository->findAll();
                    $return = array('success' => true, 'serverError' => false, 'users' => $users);
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                } else {
                    //L'utilisateur actuellement connecté n'es pas adminstrateur, on ne renvoie donc rien
                    $return = array('success' => false, 'serverError' => false);
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

    public function deleteAction() {
        //Seul l'administrateur peut supprimer un utilisateur
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try {
                if ($this->get('security.context')->isGranted('ROLE_Administrateur')) {
                    $id = $request->request->get('id_user');
                    $manager = $this->getDoctrine()->getManager();    //On récupère le manager de doctrine
                    $repository = $manager->getRepository("SiteTrailBundle:Utilisateur");
                    $usertodelete = $repository->find($id);
                    if (is_null($usertodelete)) {
                        $return = array('success' => false, 'serverError' => false, 'message' => "L'utilisateur spécifié n'existe plus dans la base de données");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                    $manager->remove($usertodelete);
                    $manager->flush();
                    //L'utilisateur a bien été supprimé
                    $return = array('success' => true, 'serverError' => false, 'message' => "L'utilisateur a bien été supprimé");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                } else {
                    //L'utilisateur n'es pas un administrateur
                    $return = array('success' => false, 'serverError' => false, 'message' => "Vous n'avez pas le droit de supprimer un utilisateur");
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

    //Récupération d'un utilisateur dans la base de données
    public function getUserAction() {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try {
                //Seul l'administrateur peut récupérer les informations  d'un utilisateur
                if ($this->get('security.context')->isGranted('ROLE_Administrateur')) {
                    //On récupère l'id de l'utilisateur que l'on souhaite rechercher
                    $id = $request->request->get('id_user');
                    //On récupère le manager de doctrine
                    $manager = $this->getDoctrine()->getManager();
                    $repository = $manager->getRepository("SiteTrailBundle:Utilisateur");
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
                    $role = $repository->find($user->getRoleId());
                    $return = array('success' => true, 'serverError' => false, 'user' => $user, 'role' => $role);
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                } else {
                    //L'utilisateur n'est pas un administrateur
                    $return = array('success' => false, 'serverError' => false, 'message' => "Vous n'avez pas le droit de supprimer un utilisateur");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
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
        $request = $this->getRequest();  //On récupère la requete courrante
        if ($request->isXmlHttpRequest()) {    //On regarde qu'il s'agit bien d'une requête AJAX
            try {
                //Seul l'administrateur peut mettre à jour un utilisateur
                if ($this->get('security.context')->isGranted('ROLE_Administrateur')) {
                    //On récupère l'identifiant de l'utilisateur à mettre à jour
                    $userToUpdate = intval($request->request->get('id_user'));
                    //On récupère son email
                    $email = $request->request->get('emailUpdate');
                    //On récupère son mot de passe
                    $password = $request->request->get('passwordUpdate');
                    //On récupère son role
                    $roleupdate = intval($request->request->get('roleUpdate'));


                    //On récupère le manager de Doctrine
                    $manager = $this->getDoctrine()->getManager();
                    //On récupère le depot role
                    $repository = $manager->getRepository("SiteTrailBundle:Role");

                    // On récupère l'utilisateur a mettre a jour
                    $user = $manager->getRepository("SiteTrailBundle:Utilisateur")->find($userToUpdate);
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
                    $userWithEmail = $manager->getRepository('SiteTrailBundle:Utilisateur')->findOneBy(array('email' => $email));

                    if (!is_null($userWithEmail) && $userWithEmail->getId() != $user->getId()) {
                        //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                        $return = array('success' => false, 'serverError' => false, 'message' => "Cet email est déjà utilisé");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                    //Si le mot de passe est renseigné, on met à jour le mot de passe
                    if ($password != "") {
                        //On récupère l'encoder factory du fichier security.yml (ici sha512)
                        $factory = $this->get('security.encoder_factory');
                        $encoder = $factory->getEncoder($user);
                        //On crypte le mot de passe avec la clé de salage
                        $password = $encoder->encodePassword($password, $user->getSalt());
                        $user->setPassword($password);    //On indique le mot de passe
                    }
                    //Si le role est null
                    if (is_null($role)) {
                        //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                        $return = array('success' => false, 'serverError' => false, 'message' => "Le role spécifié est introuvable");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }


                    //On set l'email
                    $user->setEmail($email);

                    // On définit le rôle de l'utilisateur (récupéré dans la base de donnée)
                    $user->setRoles($role);

                    //On déclenche l'enregistrement dans la base de données
                    $manager->flush();
                    //Tout s'est déroulé correctement
                    $return = array('success' => true, 'serverError' => false, 'message' => "L'utilisateur a été mis à jour");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
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
                if(!$this->isCsrfTokenValid('default', $request->get('_csrf_token')))
                {
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
                    'trace' => 1,
                    'exceptions' => 0
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

                //L'utilisateur existe dans notre base de données et il est connecté, on créé le cookie d'authentufication
                setcookie($this->container->getParameter("auth_cookie"), CustomCrypto::encrypt($membre->getId()), 0, '/');

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
    
    //Affichage du formulaire d'ajout d'un utilisateur
    

    //Affichage de la liste des membres
    public function annuaireAction() {
        
        return new Response($this->getUser()->getAvatar()->getPath());
    }
}
