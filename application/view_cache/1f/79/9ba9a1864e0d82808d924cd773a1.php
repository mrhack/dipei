<?php

/* index/index.twig */
class __TwigTemplate_1f799ba9a1864e0d82808d924cd773a1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("base/frame.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'head' => array($this, 'block_head'),
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base/frame.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Index";
    }

    // line 5
    public function block_head($context, array $blocks = array())
    {
    }

    // line 9
    public function block_header($context, array $blocks = array())
    {
        // line 10
        echo "    ";
        echo (isset($context["header"]) ? $context["header"] : null);
        echo " ";
        echo (isset($context["UID"]) ? $context["UID"] : null);
        echo "
";
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        // line 13
        echo "    <h1></h1>
    <p class=\"important\">
        Welcome on my awesome homepage.
    </p>
    ";
        // line 17
        $this->env->loadTemplate("index/block.twig")->display($context);
    }

    // line 19
    public function block_footer($context, array $blocks = array())
    {
        // line 20
        echo (isset($context["footer"]) ? $context["footer"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "index/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 20,  68 => 19,  64 => 17,  58 => 13,  55 => 12,  46 => 10,  43 => 9,  38 => 5,  32 => 3,);
    }
}
