<?php

/* base/frame.twig */
class __TwigTemplate_53514d704b40f49eac81cc295341d700 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'head' => array($this, 'block_head'),
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/xhtml\">
    <head>
        <meta charset=\"utf-8\" />
        <title>";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
        // line 7
        echo "        ";
        echo call_user_func_array($this->env->getFunction('sta')->getCallable(), array("base.css"));
        echo "

        ";
        // line 10
        echo "        ";
        $this->displayBlock('head', $context, $blocks);
        // line 13
        echo "
        ";
        // line 15
        echo "        ";
        echo call_user_func_array($this->env->getFunction('sta')->getCallable(), array("sea/sea-debug.js,config.js,sea/plugin-shim.js,lp.core.js,lp.base.js"));
        echo "
    </head>
    <body>
        <header>
            ";
        // line 19
        $this->env->loadTemplate("base/header.twig")->display($context);
        // line 20
        echo "            ";
        $this->displayBlock('header', $context, $blocks);
        // line 21
        echo "        </header>
        <div id=\"content\">";
        // line 22
        $this->displayBlock('content', $context, $blocks);
        echo "</div>
        <footer>
            ";
        // line 24
        $this->displayBlock('footer', $context, $blocks);
        // line 25
        echo "            ";
        $this->env->loadTemplate("base/footer.twig")->display($context);
        // line 26
        echo "        </footer>
        ";
        // line 28
        echo "        ";
        echo Sta::renderPageJs($context);
        echo "
    </body>
</html>";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo "lepei";
    }

    // line 10
    public function block_head($context, array $blocks = array())
    {
        // line 11
        echo "        ";
        echo call_user_func_array($this->env->getFunction('sta')->getCallable(), array((isset($context["page_css_list"]) ? $context["page_css_list"] : null)));
        echo "
        ";
    }

    // line 20
    public function block_header($context, array $blocks = array())
    {
    }

    // line 22
    public function block_content($context, array $blocks = array())
    {
    }

    // line 24
    public function block_footer($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "base/frame.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  109 => 24,  104 => 22,  99 => 20,  92 => 11,  89 => 10,  83 => 5,  75 => 28,  72 => 26,  69 => 25,  59 => 21,  56 => 20,  54 => 19,  46 => 15,  40 => 10,  34 => 7,  30 => 5,  24 => 1,  67 => 24,  65 => 19,  62 => 22,  60 => 17,  55 => 14,  53 => 13,  50 => 12,  48 => 11,  45 => 10,  43 => 13,  38 => 6,  35 => 5,  29 => 3,);
    }
}
