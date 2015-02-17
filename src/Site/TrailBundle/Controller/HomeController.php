<?php

namespace Site\TrailBundle\Controller;

use Site\TrailBundle\Entity\User;
use Site\TrailBundle\Entity\Role;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{
    public function indexAction()
    {
        /*$manager=$this->getDoctrine()->getManager();
        $repository=$manager->getRepository("SiteTrailBundle:Role");
        $listNames = array('user');
        $i = 1;
    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new User;
      $i++;
      $role=new Role();
      $role = $repository->find($i);
      // Le nom d'utilisateur et le mot de passe sont identiques
      $factory = $this->get('security.encoder_factory');
      $user->setUsername($name);
      $user->setSalt('test');
      $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($name, $user->getSalt());
        $user->setPassword($password);
      $user->setEmail("zizi");
      
      // On ne se sert pas du sel pour l'instant
      
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_USER'));
      
      $user->setRole($role);
      // On le persiste
      $manager->persist($user);
    }

    // On déclenche l'enregistrement
    $manager->flush();*/
        
        
        
       /* $manager=$this->getDoctrine()->getManager();
        $repository=$manager->getRepository("SiteTrailBundle:Role");
        $role = $repository->findOneById(2);
        
        $repository=$manager->getRepository("SiteTrailBundle:Role");
        // On crée l'utilisateur
        $user = new User;
        // Le nom d'utilisateur et le mot de passe sont identiques
        $factory = $this->get('security.encoder_factory');
        $user->setUsername("user3");
        $user->setSalt('test');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword("user3", $user->getSalt());
        $user->setPassword($password);
        $user->setEmail("zizi3");
      
        // On définit uniquement le role ROLE_USER qui est le role de base
        $user->setRoles(array('ROLE_USER'));

        $user->setRole($role);
        // On le persiste
        $manager->persist($user);
        // On déclenche l'enregistrement
        $manager->flush();*/
        
        $content = $this->get("templating")->render("SiteTrailBundle:Home:index.html.twig");
        
        
        return new Response($content);
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

