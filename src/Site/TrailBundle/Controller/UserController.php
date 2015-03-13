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



class UserController extends Controller
{
    public function indexAction()
    {        
        
        $content = $this->get("templating")->render("SiteTrailBundle:User:index.html.twig");
        
        
        return new Response($content);
    }
    
    public function createAction()  //Création d'un utilisateur
    {
        $request= $this->getRequest();  //On récupère la requete courrante
        if($request->isXmlHttpRequest())    //On regarde qu'il s'agit bien d'une requête AJAX
        {
            
            $email=$request->request->get('email'); //On récupère la variable email qui servira aussi de nom d'utilisateur
            $password=$request->request->get('password');   //On récupère le mot de passe
            $role_base=2;   //Le rôle de base (un id en gros)
            if($this->get('security.context')->isGranted('ROLE_Administrateur'))
            {
                $role_base=intval($request->request->get('role'));  //Si l'utilisateur est administrateur on récupère le rôle qu'il a passé en paramètre
            }
            $manager=$this->getDoctrine()->getManager();    //On récupère le manager de doctrine
             $repository=$manager->getRepository("SiteTrailBundle:Role");
            
              // On crée l'utilisateur
              $user = new Utilisateur();
              
              $role=new Role(); //On récupère le role de base dans la base de données
              $role = $repository->find($role_base);
              // Le nom d'utilisateur et le mot de passe sont identiques
              $factory = $this->get('security.encoder_factory');
              $user->setEmail($email);  //L'email sera le nom d'utilisateur
              $user->setSalt('test');   //clé de salage
              $encoder = $factory->getEncoder($user);   //On récupère de quoi cryprer le mot de passe
              $password = $encoder->encodePassword($password, $user->getSalt());    //On crypte le mot de passe
              $user->setPassword($password);    //On indique le mot de passe
              
              // On définit le rôle de l'utilisateur (récupéré dans la base de donnée)
              $user->setRoles($role);
              $user->setTokenics(md5(uniqid('', true)));
              // On le persiste
              $manager->persist($user);
            

            // On déclenche l'enregistrement
            $manager->flush();
            //Tou s'est déroulé correctement
            $return = array('success' => true);
 
            $response = new Response(json_encode($return));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else
        {
            return new Response("not an ajax request");
        }
    }
    
    public function loadRolesAction()
    {
        $request= $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            if($this->get('security.context')->isGranted('ROLE_Administrateur'))
            {
                $manager=$this->getDoctrine()->getManager();    //On récupère le manager de doctrine
                $repository=$manager->getRepository("SiteTrailBundle:Role");
                
                $roles = $repository->findAll();
                
                $return = array('success' => true, 'roles' => $roles);
            }
            else
            {
                $return = array('success' => false);
            }
        }
        else
        {
            $return = array('success' => false);
        }
        $response = new JsonResponse($return);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        
    }
    
    public function getAllUsersAction()
    {
       $request= $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            if($this->get('security.context')->isGranted('ROLE_Administrateur'))
            {
                $manager=$this->getDoctrine()->getManager();    //On récupère le manager de doctrine
                $repository=$manager->getRepository("SiteTrailBundle:Utilisateur");
                
                $users = $repository->findAll();
                
                $return = array('success' => true, 'users' => $users);
            }
            else
            {
                $return = array('success' => false);
            }
        }
        else
        {
            $return = array('success' => false);
        }
        $response = new JsonResponse($return);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
         
    }
    
    public function deleteAction()
    {
        
        //Seul l'admin peut delete un utilisateur
        $request= $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            if($this->get('security.context')->isGranted('ROLE_Administrateur'))
            {
                $id=$request->request->get('id_user');
                
                $manager=$this->getDoctrine()->getManager();    //On récupère le manager de doctrine
                $repository=$manager->getRepository("SiteTrailBundle:Utilisateur");
                $usertodelete = $repository->find($id);
                $manager->remove($usertodelete);
                $manager->flush();
                $return = array('success' => true);
            }
            else
            {
                $return = array('success' => false);
            }
        }
        else
        {
            $return = array('success' => false);
        }
        $response = new JsonResponse($return);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        
        
    }
    
    public function getUserAction()
    {
        $request= $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            if($this->get('security.context')->isGranted('ROLE_Administrateur'))
            {
                $id=$request->request->get('id_user');
                
                $manager=$this->getDoctrine()->getManager();    //On récupère le manager de doctrine
                $repository=$manager->getRepository("SiteTrailBundle:Utilisateur");
                $user = $repository->find($id);
                $repository=$manager->getRepository("SiteTrailBundle:Role");
                $role = $repository->find($user->getRoleId());
                $return = array('success' => true, 'user' => $user, 'role' => $role);
            }
            else
            {
                $return = array('success' => false);
            }
        }
        else
        {
            $return = array('success' => false);
        }
        $response = new JsonResponse($return);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
         
    }
    
    public function updateUserAction()
    {
        $request= $this->getRequest();  //On récupère la requete courrante
        if($request->isXmlHttpRequest())    //On regarde qu'il s'agit bien d'une requête AJAX
        {
            if($this->get('security.context')->isGranted('ROLE_Administrateur'))
            {
                $userToUpdate = intval($request->request->get('id_user'));
                $email=$request->request->get('emailUpdate'); //On récupère la variable email qui servira aussi de nom d'utilisateur
                $password=$request->request->get('passwordUpdate');   //On récupère le mot de passe
                $roleupdate=intval($request->request->get('roleUpdate'));

                    
                 $manager=$this->getDoctrine()->getManager();    //On récupère le manager de doctrine
                 $repository=$manager->getRepository("SiteTrailBundle:Role");

                  // On crée l'utilisateur
                  $user = $manager->getRepository("SiteTrailBundle:Utilisateur")->find($userToUpdate);
                  if($email!="")
                  {
                      $user->setEmail($email);  //L'email sera le nom d'utilisateur
                  }
                  
                  $role=new Role(); //On récupère le role de base dans la base de données
                  $role = $repository->find($roleupdate);
                  if($password != "")
                  {
                    $factory = $this->get('security.encoder_factory');                                      
                    $encoder = $factory->getEncoder($user);   //On récupère de quoi cryprer le mot de passe
                    $password = $encoder->encodePassword($password, $user->getSalt());    //On crypte le mot de passe
                    $user->setPassword($password);    //On indique le mot de passe
                  }

                  // On définit le rôle de l'utilisateur (récupéré dans la base de donnée)
                  $user->setRoles($role);
                  // On le persiste
                  


                // On déclenche l'enregistrement
                $manager->flush();
                //Tou s'est déroulé correctement
                $return = array('success' => true);
            }
            $response = new Response(json_encode($return));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else
        {
            return new Response("not an ajax request");
        }
    }
    
    public function annuaireAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:User:annuaire.html.twig");        
        return new Response($content);
    }
    
    
}

