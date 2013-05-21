<?php

/* index/index.twig */
class __TwigTemplate_1f799ba9a1864e0d82808d924cd773a1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo twig_escape_filter($this->env, (isset($context["content"]) ? $context["content"] : null), "html", null, true);
        echo ", \" I am \", ";
        echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : null), "html", null, true);
        echo ";
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
        return array (  19 => 1,);
    }
}
