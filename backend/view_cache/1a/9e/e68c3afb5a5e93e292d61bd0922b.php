<?php

/* widget/page.twig */
class __TwigTemplate_1a9ee68c3afb5a5e93e292d61bd0922b extends Twig_Template
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
        echo "<!-- page navigater -->
";
        // line 2
        $context["page"] = _twig_default_filter(call_user_func_array($this->env->getFunction('request')->getCallable(), array("page", "get")), 1);
        // line 3
        $context["total"] = twig_template_get_attributes($this, (isset($context["pagination"]) ? $context["pagination"] : null), "total");
        // line 4
        echo "
";
        // line 5
        if (((isset($context["total"]) ? $context["total"] : null) > 1)) {
            // line 6
            echo "<div class=\"pagination\">
  <ul>
  \t";
            // line 8
            if (((isset($context["page"]) ? $context["page"] : null) > 1)) {
                // line 9
                echo "    <li><a href=\"";
                echo call_user_func_array($this->env->getFilter('build_page_url')->getCallable(), array(((isset($context["page"]) ? $context["page"] : null) - 1)));
                echo "\">«</a></li>
    <li><a href=\"";
                // line 10
                echo call_user_func_array($this->env->getFilter('build_page_url')->getCallable(), array(1));
                echo "\">1</a></li>
    ";
            }
            // line 12
            echo "    ";
            if (((isset($context["page"]) ? $context["page"] : null) > 5)) {
                // line 13
                echo "    <li><span>...</span></li>
    ";
            }
            // line 15
            echo "    ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(((isset($context["page"]) ? $context["page"] : null) - 3), ((isset($context["page"]) ? $context["page"] : null) - 1)));
            foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                // line 16
                echo "    \t";
                if (((isset($context["p"]) ? $context["p"] : null) > 1)) {
                    // line 17
                    echo "    \t<li><a href=\"";
                    echo call_user_func_array($this->env->getFilter('build_page_url')->getCallable(), array((isset($context["p"]) ? $context["p"] : null)));
                    echo "\">";
                    echo (isset($context["p"]) ? $context["p"] : null);
                    echo "</a></li>
    \t";
                }
                // line 19
                echo "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 20
            echo "    <li><span>";
            echo (isset($context["page"]) ? $context["page"] : null);
            echo "</span></li>

    ";
            // line 22
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(((isset($context["page"]) ? $context["page"] : null) + 1), ((isset($context["page"]) ? $context["page"] : null) + 3)));
            foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                // line 23
                echo "\t    ";
                if (((isset($context["p"]) ? $context["p"] : null) < (isset($context["total"]) ? $context["total"] : null))) {
                    // line 24
                    echo "\t    <li><a href=\"";
                    echo call_user_func_array($this->env->getFilter('build_page_url')->getCallable(), array((isset($context["p"]) ? $context["p"] : null)));
                    echo "\">";
                    echo (isset($context["p"]) ? $context["p"] : null);
                    echo "</a></li>
\t    ";
                }
                // line 26
                echo "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 27
            echo "
    ";
            // line 28
            if (((isset($context["page"]) ? $context["page"] : null) < ((isset($context["total"]) ? $context["total"] : null) - 4))) {
                // line 29
                echo "    \t<li><span>...</span></li>
    ";
            }
            // line 31
            echo "    ";
            if (((isset($context["page"]) ? $context["page"] : null) < (isset($context["total"]) ? $context["total"] : null))) {
                // line 32
                echo "    \t<li><a href=\"";
                echo call_user_func_array($this->env->getFilter('build_page_url')->getCallable(), array((isset($context["total"]) ? $context["total"] : null)));
                echo "\">";
                echo (isset($context["total"]) ? $context["total"] : null);
                echo "</a></li>
    \t<li><a href=\"";
                // line 33
                echo call_user_func_array($this->env->getFilter('build_page_url')->getCallable(), array(((isset($context["page"]) ? $context["page"] : null) + 1)));
                echo "\">»</a></li>
    ";
            }
            // line 35
            echo "  </ul>
</div>
";
        }
    }

    public function getTemplateName()
    {
        return "widget/page.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  127 => 35,  122 => 33,  115 => 32,  112 => 31,  108 => 29,  106 => 28,  103 => 27,  97 => 26,  89 => 24,  82 => 22,  76 => 20,  70 => 19,  59 => 16,  54 => 15,  42 => 10,  37 => 9,  29 => 5,  22 => 2,  92 => 31,  90 => 30,  86 => 23,  77 => 26,  71 => 24,  62 => 17,  58 => 21,  55 => 20,  50 => 13,  47 => 12,  38 => 15,  35 => 8,  31 => 6,  33 => 14,  30 => 13,  28 => 12,  26 => 4,  24 => 3,  21 => 7,  19 => 1,);
    }
}
