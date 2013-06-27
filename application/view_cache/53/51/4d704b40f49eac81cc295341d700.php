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
        ob_start();
        // line 2
        echo "<!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/xhtml\">
    <head>
        <meta charset=\"utf-8\" />
        <title>";
        // line 6
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
        // line 8
        echo "        ";
        echo call_user_func_array($this->env->getFunction('sta')->getCallable(), array("base.css"));
        echo "

        ";
        // line 11
        echo "        ";
        $this->displayBlock('head', $context, $blocks);
        // line 14
        echo "
        ";
        // line 16
        echo "        ";
        echo call_user_func_array($this->env->getFunction('sta')->getCallable(), array("sea/sea-debug.js,config.js,sea/plugin-shim.js,lp.core.js,lp.base.js"));
        echo "
    </head>
    <body>
        <header>
            ";
        // line 20
        $this->env->loadTemplate("base/header.twig")->display($context);
        // line 21
        echo "            ";
        $this->displayBlock('header', $context, $blocks);
        // line 22
        echo "        </header>
        <div id=\"content\">";
        // line 23
        $this->displayBlock('content', $context, $blocks);
        echo "</div>
        <footer>
            ";
        // line 25
        $this->displayBlock('footer', $context, $blocks);
        // line 26
        echo "            ";
        $this->env->loadTemplate("base/footer.twig")->display($context);
        // line 27
        echo "        </footer>
        ";
        // line 29
        echo "        ";
        echo Sta::renderPageJs($context);
        echo "
    </body>
</html>
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    // line 6
    public function block_title($context, array $blocks = array())
    {
        echo "lepei";
    }

    // line 11
    public function block_head($context, array $blocks = array())
    {
        // line 12
        echo "        ";
        echo call_user_func_array($this->env->getFunction('sta')->getCallable(), array((isset($context["page_css_list"]) ? $context["page_css_list"] : null)));
        echo "
        ";
    }

    // line 21
    public function block_header($context, array $blocks = array())
    {
    }

    // line 23
    public function block_content($context, array $blocks = array())
    {
    }

    // line 25
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
        return array (  113 => 25,  108 => 23,  103 => 21,  96 => 12,  93 => 11,  87 => 6,  77 => 29,  74 => 27,  71 => 26,  69 => 25,  64 => 23,  61 => 22,  58 => 21,  56 => 20,  48 => 16,  45 => 14,  42 => 11,  36 => 8,  32 => 6,  26 => 2,  24 => 1,);
    }
}
