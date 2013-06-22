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
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["loc_list"]) ? $context["loc_list"] : null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["lid"]) {
            // line 20
            echo "                ";
            $this->env->loadTemplate("widget/right/loc.twig")->display(array_merge($context, array("loc" => twig_template_get_attributes($this, (isset($context["LOCS"]) ? $context["LOCS"] : null), (isset($context["lid"]) ? $context["lid"] : null), array(), "array"))));
            // line 21
            echo "            ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['lid'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
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
        return array (  99 => 22,  85 => 21,  82 => 20,  65 => 19,  62 => 18,  60 => 17,  55 => 14,  53 => 13,  50 => 12,  48 => 11,  45 => 10,  43 => 9,  38 => 6,  35 => 5,  29 => 3,);
    }
}
