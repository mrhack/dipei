<?php

/* index/index.twig */
class __TwigTemplate_f546353a7967b5c83f7409f3891a4b64 extends Twig_Template
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
        $context["columns"] = array("列名1" => "name", "列名2" => "id", "列名3" => "\$data[type]==1?'aaaa':'bbb'", "列名4" => "<a href=\\\"\\\">\$data[name]</a>");
        // line 7
        $context["data_list"] = array(0 => array("name" => "hahaha", "id" => 1212, "type" => 1));
        // line 8
        $this->env->loadTemplate("base/data-grid.twig")->display($context);
        // line 9
        echo "
";
        // line 10
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
        return array (  28 => 10,  25 => 9,  23 => 8,  21 => 7,  19 => 1,);
    }
}
