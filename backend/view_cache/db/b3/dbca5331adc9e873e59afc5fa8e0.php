<?php

/* base/data-grid.twig */
class __TwigTemplate_dbb3dbca5331adc9e873e59afc5fa8e0 extends Twig_Template
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
        // line 6
        echo "
<section class=\"data-grid\">
    <!-- navigator -->

    <!-- data table -->
    <table>
        <tr>
            ";
        // line 13
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["columns"]) ? $context["columns"] : null));
        foreach ($context['_seq'] as $context["desc"] => $context["key"]) {
            // line 14
            echo "            <th>
                ";
            // line 15
            echo (isset($context["desc"]) ? $context["desc"] : null);
            echo "
            </th>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['desc'], $context['key'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 18
        echo "        </tr>
        ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["data_list"]) ? $context["data_list"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["data"]) {
            // line 20
            echo "        <tr>
            ";
            // line 21
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["columns"]) ? $context["columns"] : null));
            foreach ($context['_seq'] as $context["desc"] => $context["key"]) {
                // line 22
                echo "                <td>";
                echo call_user_func_array($this->env->getFilter('eval_str')->getCallable(), array((isset($context["key"]) ? $context["key"] : null), (isset($context["data"]) ? $context["data"] : null)));
                echo "</td>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['desc'], $context['key'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 24
            echo "        </tr>
        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 26
            echo "        <tr class=\"no-data\">";
            echo AppLocal::getString("暂无数据");
            echo "</tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['data'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        echo "    </table>
    <!-- navigator -->
</section>";
    }

    public function getTemplateName()
    {
        return "base/data-grid.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  83 => 28,  74 => 26,  68 => 24,  59 => 22,  55 => 21,  52 => 20,  47 => 19,  44 => 18,  35 => 15,  32 => 14,  28 => 13,  19 => 6,);
    }
}
