<?php

/* index/index.twig */
class __TwigTemplate_6ee00c725c33a47a5c0c71a540358835 extends Twig_Template
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
        echo (isset($context["hello"]) ? $context["hello"] : null);
    }

    public function getTemplateName()
    {
        return "index/index.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
