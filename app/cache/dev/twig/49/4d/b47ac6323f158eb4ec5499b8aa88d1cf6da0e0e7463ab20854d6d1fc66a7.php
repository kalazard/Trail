<?php

/* SiteTrailBundle::layout.html.twig */
class __TwigTemplate_494db47ac6323f158eb4ec5499b8aa88d1cf6da0e0e7463ab20854d6d1fc66a7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE HTML>
<html>
  <head>
      <link href=\"//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css\" rel=\"stylesheet\">
      ";
        // line 5
        if (isset($context['assetic']['debug']) && $context['assetic']['debug']) {
            // asset "4ae2140_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_4ae2140_0") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/4ae2140_menu_1.css");
            // line 7
            echo "        <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" type=\"text/css\" />
      ";
        } else {
            // asset "4ae2140"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_4ae2140") : $this->env->getExtension('assets')->getAssetUrl("_controller/css/4ae2140.css");
            echo "        <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\" type=\"text/css\" />
      ";
        }
        unset($context["asset_url"]);
        // line 9
        echo "      <script src=\"//code.jquery.com/jquery-1.11.2.min.js\"></script>
      <script src=\"//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js\"></script>
      <!--<script src=\"../../src/Site/TrailBundle/Resources/js/menu.js\"></script>-->
      ";
        // line 12
        if (isset($context['assetic']['debug']) && $context['assetic']['debug']) {
            // asset "08ad7e7_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_08ad7e7_0") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/08ad7e7_part_1_menu_1.js");
            // line 13
            echo "        <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
        } else {
            // asset "08ad7e7"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_08ad7e7") : $this->env->getExtension('assets')->getAssetUrl("_controller/js/08ad7e7.js");
            echo "        <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : $this->getContext($context, "asset_url")), "html", null, true);
            echo "\"></script>
    ";
        }
        unset($context["asset_url"]);
        // line 15
        echo "      
    <meta charset=\"utf-8\">
    <title>";
        // line 17
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
  </head>
  <body>
    <div id=\"wrapper\">
        <div class=\"overlay\"></div>
    
        <!-- Sidebar -->
        <nav class=\"navbar navbar-inverse navbar-fixed-top\" id=\"sidebar-wrapper\" role=\"navigation\">
            <ul class=\"nav sidebar-nav\">
                <li class=\"sidebar-brand\">
                    <a href=\"#\">
                       Trail
                    </a>
                </li>
                <img class=\"avatar\" src=\"https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120\"
                    alt=\"\" href=\"#signup\" data-toggle=\"modal\" data-target=\".bs-modal-sm\">
                <li>
                    <a href=\"#\">Home</a>
                </li>
                <li>
                    <a href=\"#\">About</a>
                </li>
                <li>
                    <a href=\"#\">Events</a>
                </li>
                <li>
                    <a href=\"#\">Team</a>
                </li>
                <li class=\"dropdown\">
                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">Works <span class=\"caret\"></span></a>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li class=\"dropdown-header\">Dropdown heading</li>
                    <li><a href=\"#\">Action</a></li>
                    <li><a href=\"#\">Another action</a></li>
                    <li><a href=\"#\">Something else here</a></li>
                    <li><a href=\"#\">Separated link</a></li>
                    <li><a href=\"#\">One more separated link</a></li>
                  </ul>
                </li>
                <li>
                    <a href=\"#\">Services</a>
                </li>
                <li>
                    <a href=\"#\">Contact</a>
                </li>
                <li>
                    <a href=\"https://twitter.com/maridlcrmn\">Follow me</a>
                </li>
            </ul>
        </nav>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id=\"page-content-wrapper\">
            <button type=\"button\" class=\"hamburger is-closed\" data-toggle=\"offcanvas\">
                <span class=\"hamb-top\"></span>
    \t\t\t<span class=\"hamb-middle\"></span>
\t\t\t\t<span class=\"hamb-bottom\"></span>
            </button>
            
            ";
        // line 77
        $this->displayBlock('body', $context, $blocks);
        // line 79
        echo "            <!--<div class=\"container\">
                <div class=\"row\">
                    <div class=\"col-lg-8 col-lg-offset-2\">
                        
                    </div>
                </div>
            </div>-->
        </div>
        
        <!-- /#page-content-wrapper -->

    </div>

<div class=\"modal fade bs-modal-sm\" id=\"myModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"mySmallModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-sm\">
      <div class=\"modal-content\">
          <br>
          <div class=\"bs-example bs-example-tabs\">
              <ul id=\"myTab\" class=\"nav nav-tabs\">
                <li class=\"active\"><a href=\"#signin\" data-toggle=\"tab\">Se connecter</a></li>
                <li class=\"\"><a href=\"#signup\" data-toggle=\"tab\">S'inscrire</a></li>
                
              </ul>
          </div>
        <div class=\"modal-body\">
          <div id=\"myTabContent\" class=\"tab-content\">
          
          <div class=\"tab-pane fade active in\" id=\"signin\">
              <form id=\"loginform\" action=\"";
        // line 107
        echo $this->env->getExtension('routing')->getPath("login_check");
        echo "\" method=\"post\" class=\"form-horizontal\">
              <fieldset>
              <!-- Sign In Form -->
              <!-- Text input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"_username\">Login :</label>
                <div class=\"controls\">
                  <input required=\"\" id=\"userid\" name=\"_username\" type=\"text\" class=\"form-control\" placeholder=\"login\" class=\"input-medium\" required=\"\">
                </div>
              </div>

              <!-- Password input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"_password\">Mot de passe :</label>
                <div class=\"controls\">
                  <input required=\"\" id=\"passwordinput\" name=\"_password\" class=\"form-control\" type=\"password\" placeholder=\"********\" class=\"input-medium\">
                </div>
              </div>

              <!-- Button -->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"signin\"></label>
                <div class=\"controls\">
                  <button id=\"signin\" name=\"signin\" class=\"btn btn-success\">Se connecter</button>
                </div>
              </div>
              
              </fieldset>
              </form>
          </div>
          <div class=\"tab-pane fade\" id=\"signup\">
              <form class=\"form-horizontal\">
              <fieldset>
              <!-- Sign Up Form -->
              <!-- Text input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"Email\">Email :</label>
                <div class=\"controls\">
                  <input id=\"Email\" name=\"Email\" class=\"form-control\" type=\"email\" placeholder=\"exemple@exemple.com\" class=\"input-large\" required=\"\">
                </div>
              </div>

             

              <!-- Password input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"password\">Mot de passe :</label>
                <div class=\"controls\">
                  <input id=\"password\" name=\"password\" class=\"form-control\" type=\"password\" placeholder=\"********\" class=\"input-large\" required=\"\">
                  
                </div>
              </div>

              <!-- Text input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"reenterpassword\">Ressaisir le mot de passe :</label>
                <div class=\"controls\">
                  <input id=\"reenterpassword\" class=\"form-control\" name=\"reenterpassword\" type=\"password\" placeholder=\"********\" class=\"input-large\" required=\"\">
                </div>
              </div>

              
              <br>
              

              <!-- Button -->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"confirmsignup\"></label>
                <div class=\"controls\">
                  <button id=\"confirmsignup\" name=\"confirmsignup\" class=\"btn btn-success\">S'inscrire</button>
                </div>
              </div>
              </fieldset>
              </form>
        </div>
      </div>
        </div>
        <div class=\"modal-footer\">
        <center>
          <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fermer</button>
          </center>
        </div>
      </div>
    </div>
</div>    
<script>

\$(\"#loginform\").submit(function(){ 
    
    
    
    var username = \$(\"#userid\").val();
    var password = \$(\"#passwordinput\").val();
    alert(username+password);
    \$.ajax({
        type: \"POST\",
        url: \"";
        // line 203
        echo $this->env->getExtension('routing')->getPath("login_check");
        echo "\",
        data: {_username: username, _password: password},
        cache: false,
        success: function(data){
           alert(data.success);
           if(data.success)
           {
               location.reload();
           }
           else
           {
              alert(data.message);
           }
               
        
        }
    }); 
    return false;
    
});
</script>
  </body>
</html>";
    }

    // line 17
    public function block_title($context, array $blocks = array())
    {
        echo "Trail";
    }

    // line 77
    public function block_body($context, array $blocks = array())
    {
        // line 78
        echo "            ";
    }

    public function getTemplateName()
    {
        return "SiteTrailBundle::layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  302 => 78,  299 => 77,  293 => 17,  266 => 203,  167 => 107,  137 => 79,  135 => 77,  72 => 17,  68 => 15,  54 => 13,  50 => 12,  45 => 9,  31 => 7,  27 => 5,  21 => 1,);
    }
}
