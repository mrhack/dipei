<?php

/* error/error.twig */
class __TwigTemplate_4b88617fbeb91a2a376c68581ea18f1a extends Twig_Template
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
        echo twig_template_get_attributes($this, (isset($context["user"]) ? $context["user"] : null), "name");
    }

    public function getTemplateName()
    {
        return "error/error.twig";
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
