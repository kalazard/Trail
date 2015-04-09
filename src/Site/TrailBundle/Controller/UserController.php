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
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "Le nom ne doît pas être vide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Si la date de naissance est vide
                if ($datenaissance == "") {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "La date de naissance ne doît pas être vide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Si le prenom est vide
                if ($prenom == "") {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "Le prénom ne doît pas être vide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Si le telephone est vide
                if ($telephone == "") {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "Le telephone ne doît pas être vide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
                //Si la licence est vide
                if ($licence == "") {
                    //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                    $return = array('success' => false, 'serverError' => false, 'message' => "La licence ne doît pas être vide");
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
                if (!filter_var($licence, FILTER_VALIDATE_URL)) {
                    $return = array('success' => false, 'serverError' => false, 'message' => "La licence doit être un lien valide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }


                $datenaissance = DateTime::createFromFormat('d/m/Y', $datenaissance);
                $date_errors = DateTime::getLastErrors();
                if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
                    $return = array('success' => false, 'serverError' => false, 'message' => "Le format de la date est invalide");
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
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
                $manager->persist($user);

                //On déclenche l'enregistrement dans la base de données
                $manager->flush();
                //Ensuite on essaye de se connecter avec le webservice
                $clientSOAP = new SoapClient(null, array(
                    'uri' => $this->container->getParameter("auth_server_host"),
                    'location' => $this->container->getParameter("auth_server_host"),
                    'trace' => 1,
                    'exceptions' => 0
                ));

                //On appel la méthode du webservice qui permet de se connecter
                $response = $clientSOAP->__call('createUser', array('id' => CustomCrypto::encrypt($user->getId()), 'username' => CustomCrypto::encrypt($email), 'password' => CustomCrypto::encrypt($password), 'server' => CustomCrypto::encrypt($_SERVER['SERVER_ADDR'])));
                //L'utilisateur n'existe pas dans la base de données ou les identifiants sont incorrects

                if ($response['error'] == true) {
                    $return = array('success' => false, 'serverError' => false, 'message' => $response['message']);
                    $response = new Response(json_encode($return));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
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
                if ($this->get('security.context')->isGranted('ROLE_Administrateur') || $this->get('security.context')->isGranted('ROLE_Utilisateur')) {
                    //On récupère le manager de Doctrine
                    $manager = $this->getDoctrine()->getManager();
                    //On récupère le dépôt utilisateur
                    $repository = $manager->getRepository("SiteTrailBundle:Membre");
                    //On récupère tous les utilisateurs
                    $users = $repository->findAll();
                    $visibilite = $this->get('security.context')->isGranted('ROLE_Administrateur');
                    $return = array('success' => true, 'serverError' => false, 'users' => $users, 'visibilite' => $visibilite);
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
                    $repository = $manager->getRepository("SiteTrailBundle:Membre");
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
                } else {
                    //L'utilisateur n'est pas un administrateur
                    $return = array('success' => false, 'serverError' => false, 'message' => "Vous n'avez pas le droit de modifier un utilisateur");
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
                        //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                        $return = array('success' => false, 'serverError' => false, 'message' => "Le nom ne doît pas être vide");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                    //Si la date de naissance est vide
                    if ($datenaissance == "") {
                        //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                        $return = array('success' => false, 'serverError' => false, 'message' => "La date de naissance ne doît pas être vide");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                    //Si le prenom est vide
                    if ($prenom == "") {
                        //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                        $return = array('success' => false, 'serverError' => false, 'message' => "Le prénom ne doît pas être vide");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                    //Si le telephone est vide
                    if ($telephone == "") {
                        //success = false car l'opération de création à échoué, serverError = false car ce n'est pas uen erreure côté serveur 
                        $return = array('success' => false, 'serverError' => false, 'message' => "Le telephone ne doît pas être vide");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }
                    
                    $datenaissance = DateTime::createFromFormat('d/m/Y', $datenaissance);
                    $date_errors = DateTime::getLastErrors();
                    if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
                        $return = array('success' => false, 'serverError' => false, 'message' => "Le format de la date est invalide");
                        $response = new Response(json_encode($return));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
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

    //Affichage du formulaire d'ajout d'un utilisateur
    //Affichage de la liste des membres
    public function annuaireAction() {

        $content = $this->get("templating")->render("SiteTrailBundle:User:annuaire.html.twig");

        return new Response($content);
    }

}
