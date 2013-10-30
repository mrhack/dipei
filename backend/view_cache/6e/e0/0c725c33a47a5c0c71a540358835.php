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
        $context["columns"] = array("列名1" => "name", "列名2" => "id", "列名3" => "\$data[type]==1?'aaaa':'bbb'", "列名4" => "\"<a href=\\\"\$data[id]\\\">\$data[name]</a>\"");
        // line 7
        echo "
";
        // line 8
        $context["pagination"] = array("total" => 10);
        // line 11
        $context["data_list"] = array(0 => array("name" => "hahaha", "id" => 1212, "type" => 1), 1 => array("name" => "fdfdfdf", "id" => 12222, "type" => 2));
        // line 12
        $this->env->loadTemplate("base/data-grid.twig")->display($context);
        // line 13
        echo "
";
        // line 14
        echo (isset($context["hello"]) ? $context["hello"] : null);
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
        return array (  33 => 14,  30 => 13,  28 => 12,  26 => 11,  24 => 8,  21 => 7,  19 => 1,);
    }
}
