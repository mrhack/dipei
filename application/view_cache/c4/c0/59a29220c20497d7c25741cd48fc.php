<?php

/* reg/index.twig */
class __TwigTemplate_c4c059a29220c20497d7c25741cd48fc extends Twig_Template
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
        echo "register page
name:";
        // line 2
        echo (isset($context["name"]) ? $context["name"] : null);
        echo "
email:";
        // line 3
        echo (isset($context["email"]) ? $context["email"] : null);
        echo "
password:";
        // line 4
        echo (isset($context["password"]) ? $context["password"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "reg/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 4,  26 => 3,  22 => 2,  19 => 1,);
    }
}
