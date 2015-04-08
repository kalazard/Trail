<?php

namespace Site\TrailBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use \Site\TrailBundle\Entity\Membre;

class CustomUserProvider implements UserProviderInterface {

    protected $entityManager;


    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->entityManager = $em;
        
        
    }
    
    public function getCookieToken() {
        //Permet de récupérer l'id de l'utilisateur dans le cookie !        
        if(isset($_COOKIE["TrailAuthCookie"]))
        {
            $cookie = CustomCrypto::decrypt($_COOKIE["TrailAuthCookie"]);
            return intval(explode("/", $cookie)[0]);
        }
        else
        {
            return false;
        }

        
    }

    public function loadUserByUsername($userid) {
        
        // On récupère le membre dans la base de données si il existe
        $membre = $this->entityManager->getRepository("SiteTrailBundle:Membre")->find($userid);
        
        return $membre;
        
    }

    public function refreshUser(UserInterface $user) {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        
        if(isset($_COOKIE["TrailAuthCookie"]))
        {
            return $user;
        }
        else
        {
            throw new UnsupportedUserException();
        }
        
        //throw new UnsupportedUserException();
    }

    public function supportsClass($class) {
        return 'Site\TrailBundle\Entity\Membre' === $class;
    }

}
