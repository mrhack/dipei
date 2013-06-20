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
            'content' => array($this, 'block_content'),
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
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "    <section class=\"layout-400-580\">
        <div class=\"layout-l\">
            <!--search-->
            ";
        // line 9
        $this->env->loadTemplate("widget/search/search.twig")->display($context);
        // line 10
        echo "            <!--loc list-->
            ";
        // line 11
        $this->env->loadTemplate("widget/left/loc.twig")->display($context);
        // line 12
        echo "            <!--why-->
            ";
        // line 13
        $this->env->loadTemplate("widget/left/why.twig")->display($context);
        // line 14
        echo "        </div>
        <div class=\"layout-r\">
            <!--my history-->
            ";
        // line 17
        $this->env->loadTemplate("widget/right/history.twig")->display($context);
        // line 18
        echo "            <!-- loc -->
            ";
        // line 19
        $this->env->loadTemplate("widget/right/loc.twig")->display($context);
        // line 20
        echo "        </div>
    </section>
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
        return array (  67 => 20,  65 => 19,  62 => 18,  60 => 17,  55 => 14,  53 => 13,  50 => 12,  48 => 11,  45 => 10,  43 => 9,  38 => 6,  35 => 5,  29 => 3,);
    }
}
