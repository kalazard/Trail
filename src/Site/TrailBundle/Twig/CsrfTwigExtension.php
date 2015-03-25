<?php

namespace Site\TrailBundle\Twig;

class CsrfTwigExtension extends \Twig_Extension{

    protected $csrfProvider;

    public function __construct($csrfProvider){
        $this->csrfProvider = $csrfProvider;
    }

    public function getName(){
        return 'csrf_twig_extension';
    }

    public function getFunctions(){
        return array(
            'default_csrf_token' => new \Twig_Function_Method($this, 'getCsrfToken'),
        );
    }

    public function getCsrfToken(){
        return $this->csrfProvider->generateCsrfToken('default');
    }

}

