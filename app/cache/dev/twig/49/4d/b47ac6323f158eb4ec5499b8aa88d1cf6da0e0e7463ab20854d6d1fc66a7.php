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
      <script src=\"../../src/Site/TrailBundle/Resources/js/menu.js\"></script>
      
    <meta charset=\"utf-8\">
    <title>";
        // line 14
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
        // line 74
        $this->displayBlock('body', $context, $blocks);
        // line 76
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
                <li class=\"active\"><a href=\"#signin\" data-toggle=\"tab\">Sign In</a></li>
                <li class=\"\"><a href=\"#signup\" data-toggle=\"tab\">Register</a></li>
                <li class=\"\"><a href=\"#why\" data-toggle=\"tab\">Why?</a></li>
              </ul>
          </div>
        <div class=\"modal-body\">
          <div id=\"myTabContent\" class=\"tab-content\">
          <div class=\"tab-pane fade in\" id=\"why\">
          <p>We need this information so that you can receive access to the site and its content. Rest assured your information will not be sold, traded, or given to anyone.</p>
          <p></p><br> Please contact <a mailto:href=\"JoeSixPack@Sixpacksrus.com\"></a>JoeSixPack@Sixpacksrus.com</a> for any other inquiries.</p>
          </div>
          <div class=\"tab-pane fade active in\" id=\"signin\">
              <form class=\"form-horizontal\">
              <fieldset>
              <!-- Sign In Form -->
              <!-- Text input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"userid\">Alias:</label>
                <div class=\"controls\">
                  <input required=\"\" id=\"userid\" name=\"userid\" type=\"text\" class=\"form-control\" placeholder=\"JoeSixpack\" class=\"input-medium\" required=\"\">
                </div>
              </div>

              <!-- Password input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"passwordinput\">Password:</label>
                <div class=\"controls\">
                  <input required=\"\" id=\"passwordinput\" name=\"passwordinput\" class=\"form-control\" type=\"password\" placeholder=\"********\" class=\"input-medium\">
                </div>
              </div>

              <!-- Multiple Checkboxes (inline) -->
              

              <!-- Button -->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"signin\"></label>
                <div class=\"controls\">
                  <button id=\"signin\" name=\"signin\" class=\"btn btn-success\">Sign In</button>
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
                <label class=\"control-label\" for=\"Email\">Email:</label>
                <div class=\"controls\">
                  <input id=\"Email\" name=\"Email\" class=\"form-control\" type=\"text\" placeholder=\"JoeSixpack@sixpacksrus.com\" class=\"input-large\" required=\"\">
                </div>
              </div>

              <!-- Text input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"userid\">Alias:</label>
                <div class=\"controls\">
                  <input id=\"userid\" name=\"userid\" class=\"form-control\" type=\"text\" placeholder=\"JoeSixpack\" class=\"input-large\" required=\"\">
                </div>
              </div>

              <!-- Password input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"password\">Password:</label>
                <div class=\"controls\">
                  <input id=\"password\" name=\"password\" class=\"form-control\" type=\"password\" placeholder=\"********\" class=\"input-large\" required=\"\">
                  <em>1-8 Characters</em>
                </div>
              </div>

              <!-- Text input-->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"reenterpassword\">Re-Enter Password:</label>
                <div class=\"controls\">
                  <input id=\"reenterpassword\" class=\"form-control\" name=\"reenterpassword\" type=\"password\" placeholder=\"********\" class=\"input-large\" required=\"\">
                </div>
              </div>

              <!-- Multiple Radios (inline) -->
              <br>
              

              <!-- Button -->
              <div class=\"control-group\">
                <label class=\"control-label\" for=\"confirmsignup\"></label>
                <div class=\"controls\">
                  <button id=\"confirmsignup\" name=\"confirmsignup\" class=\"btn btn-success\">Sign Up</button>
                </div>
              </div>
              </fieldset>
              </form>
        </div>
      </div>
        </div>
        <div class=\"modal-footer\">
        <center>
          <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
          </center>
        </div>
      </div>
    </div>
</div>    

  </body>
</html>";
    }

    // line 14
    public function block_title($context, array $blocks = array())
    {
        echo "Trail";
    }

    // line 74
    public function block_body($context, array $blocks = array())
    {
        // line 75
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
        return array (  256 => 75,  253 => 74,  247 => 14,  117 => 76,  115 => 74,  52 => 14,  45 => 9,  31 => 7,  27 => 5,  21 => 1,);
    }
}
