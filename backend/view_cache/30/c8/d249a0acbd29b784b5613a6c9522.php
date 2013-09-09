<?php

/* base/macro.twig */
class __TwigTemplate_30c8d249a0acbd29b784b5613a6c9522 extends Twig_Template
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
        // line 23
        echo "

";
        // line 35
        echo "

";
        // line 42
        echo "

";
        // line 54
        echo "



";
        // line 66
        echo "
";
        // line 77
        echo "
";
        // line 83
        echo "

";
        // line 127
        echo "

";
    }

    // line 3
    public function getuserhead($_user = null, $_outersize = null, $_noLink = null)
    {
        $context = $this->env->mergeGlobals(array(
            "user" => $_user,
            "outersize" => $_outersize,
            "noLink" => $_noLink,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 4
            echo "    <div class=\"radiu-head box-s\" style=\"width:";
            echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
            echo "px;height:";
            echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
            echo "px\">
        ";
            // line 5
            if ((isset($context["noLink"]) ? $context["noLink"] : null)) {
                // line 6
                echo "        <img title=\"";
                echo $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "name");
                echo "\" src=\"";
                echo Sta::url($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "head"), "head", ((isset($context["outersize"]) ? $context["outersize"] : null) - 12));
                echo "\" width=\"";
                echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
                echo "\" height=\"";
                echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
                echo "\" 
        style=\"width:";
                // line 7
                echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
                echo "px;height:";
                echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
                echo "px;\"
        alt=\"\" />
        ";
            } else {
                // line 10
                echo "        <a href=\"/detail/";
                echo $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "id");
                echo "/\" target=\"_blank\">
            <img title=\"";
                // line 11
                echo $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "name");
                echo "\" src=\"";
                echo Sta::url($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "head"), "head", ((isset($context["outersize"]) ? $context["outersize"] : null) - 12));
                echo "\" width=\"";
                echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
                echo "\" height=\"";
                echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
                echo "\"
        style=\"width:";
                // line 12
                echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
                echo "px;height:";
                echo ((isset($context["outersize"]) ? $context["outersize"] : null) - 12);
                echo "px;\"
        alt=\"\" />
        </a>
        ";
            }
            // line 16
            echo "    </div>
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 19
    public function getusername($_user = null)
    {
        $context = $this->env->mergeGlobals(array(
            "user" => $_user,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 20
            echo "    <a class=\"u-name\" href=\"/detail/";
            echo $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "id");
            echo "/\">";
            echo $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "name");
            echo "</a>
    ";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 26
    public function getprojectnum($_projects = null, $_PROJECTS = null)
    {
        $context = $this->env->mergeGlobals(array(
            "projects" => $_projects,
            "PROJECTS" => $_PROJECTS,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 27
            echo "    ";
            $context["num"] = 0;
            // line 28
            echo "    ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["projects"]) ? $context["projects"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["pid"]) {
                // line 29
                echo "        ";
                if (($this->getAttribute($this->getAttribute((isset($context["PROJECTS"]) ? $context["PROJECTS"] : null), (isset($context["pid"]) ? $context["pid"] : null), array(), "array"), "status") >= 10)) {
                    // line 30
                    echo "            ";
                    $context["num"] = ((isset($context["num"]) ? $context["num"] : null) + 1);
                    // line 31
                    echo "        ";
                }
                // line 32
                echo "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pid'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 33
            echo "    ";
            echo (isset($context["num"]) ? $context["num"] : null);
            echo "
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 38
    public function getuserstatus($_user = null)
    {
        $context = $this->env->mergeGlobals(array(
            "user" => $_user,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 39
            echo "    <i class=\"i-u i-";
            echo (($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "online")) ? ("online") : ("offline"));
            echo "\"></i>
    <span class=\"c999\"> ";
            // line 40
            echo (($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "online")) ? (AppLocal::getString("在线")) : (AppLocal::getString("离线")));
            echo "</span>
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 45
    public function getuseraddr($_user = null, $_LOCATIONS = null)
    {
        $context = $this->env->mergeGlobals(array(
            "user" => $_user,
            "LOCATIONS" => $_LOCATIONS,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 46
            echo "    ";
            $context["loc"] = $this->getAttribute((isset($context["LOCATIONS"]) ? $context["LOCATIONS"] : null), $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "lid"), array(), "array");
            // line 47
            echo "    ";
            if ((twig_length_filter($this->env, $this->getAttribute((isset($context["loc"]) ? $context["loc"] : null), "path")) >= 2)) {
                // line 48
                echo "        ";
                $context["country"] = $this->getAttribute((isset($context["LOCATIONS"]) ? $context["LOCATIONS"] : null), $this->getAttribute($this->getAttribute((isset($context["loc"]) ? $context["loc"] : null), "path"), 1, array(), "array"), array(), "array");
                // line 49
                echo "        <a href=\"/loc/city/";
                echo $this->getAttribute((isset($context["loc"]) ? $context["loc"] : null), "id");
                echo "/\"><span class=\"u-city\">";
                echo $this->getAttribute((isset($context["loc"]) ? $context["loc"] : null), "name");
                echo "</span></a>,<a href=\"/loc/";
                echo $this->getAttribute((isset($context["country"]) ? $context["country"] : null), "id");
                echo "/\"><span class=\"u-country\">";
                echo $this->getAttribute((isset($context["country"]) ? $context["country"] : null), "name");
                echo "</span><i class=\"i-country i-";
                echo $this->getAttribute($this->getAttribute((isset($context["loc"]) ? $context["loc"] : null), "path"), 1, array(), "array");
                echo "\"></i></a>
    ";
            } else {
                // line 51
                echo "        <a href=\"/loc/city/";
                echo $this->getAttribute((isset($context["loc"]) ? $context["loc"] : null), "id");
                echo "/\"><span class=\"u-city\">";
                echo $this->getAttribute((isset($context["loc"]) ? $context["loc"] : null), "name");
                echo "</span></a>
    ";
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 60
    public function getdateselect($_desc = null, $_style = null)
    {
        $context = $this->env->mergeGlobals(array(
            "desc" => $_desc,
            "style" => $_style,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 61
            echo "    <label class=\"input-widget j-datepicker\" style=\"";
            echo (isset($context["style"]) ? $context["style"] : null);
            echo "\">
        <span class=\"input-val\">";
            // line 62
            echo (isset($context["desc"]) ? $context["desc"] : null);
            echo "</span>
        <i class=\"i-icon i-date\"></i>
    </label>
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 68
    public function getprojectstatus($_state = null)
    {
        $context = $this->env->mergeGlobals(array(
            "state" => $_state,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 69
            echo "    ";
            if (((isset($context["state"]) ? $context["state"] : null) == 0)) {
                // line 70
                echo "        <span style=\"color:red;\">[";
                echo AppLocal::getString("审核中");
                echo "]</span>
    ";
            } elseif (((isset($context["state"]) ? $context["state"] : null) == 1)) {
                // line 72
                echo "        <span style=\"color:green;\">[";
                echo AppLocal::getString("审核通过");
                echo "]</span>
    ";
            } elseif (((isset($context["state"]) ? $context["state"] : null) == (-1))) {
                // line 74
                echo "        <span style=\"color:black;\">[";
                echo AppLocal::getString("审核不通过");
                echo "]</span>
    ";
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 79
    public function getprice($_p = null)
    {
        $context = $this->env->mergeGlobals(array(
            "p" => $_p,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 80
            echo "    <span class=\"price-unit mgr10\">";
            echo $this->getAttribute($this->getAttribute(call_user_func_array($this->env->getFunction('var')->getCallable(), array("MONEYS")), $this->getAttribute((isset($context["p"]) ? $context["p"] : null), "price_unit"), array(), "array"), "symbol");
            echo "</span>
    <span class=\"price-num\">";
            // line 81
            echo call_user_func_array($this->env->getFilter('number_format')->getCallable(), array($this->getAttribute((isset($context["p"]) ? $context["p"] : null), "price")));
            echo "</span>
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 87
    public function getrenderdate($_data = null)
    {
        $context = $this->env->mergeGlobals(array(
            "data" => $_data,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 88
            echo "    ";
            $context["year"] = $this->getAttribute((isset($context["data"]) ? $context["data"] : null), 0, array(), "array");
            // line 89
            echo "    ";
            $context["month"] = $this->getAttribute((isset($context["data"]) ? $context["data"] : null), 1, array(), "array");
            // line 90
            echo "    ";
            $context["day"] = $this->getAttribute((isset($context["data"]) ? $context["data"] : null), 2, array(), "array");
            // line 91
            echo "
    <select name=\"";
            // line 92
            echo $this->getAttribute((isset($context["year"]) ? $context["year"] : null), "name");
            echo "\">
        <option value=\"\">";
            // line 93
            echo AppLocal::getString("请选择");
            echo "</option>
        ";
            // line 94
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1950, $this->getAttribute(twig_date_converter($this->env), "format", array(0 => "Y"), "method")));
            foreach ($context['_seq'] as $context["_key"] => $context["n"]) {
                // line 95
                echo "        <option value=\"";
                echo (isset($context["n"]) ? $context["n"] : null);
                echo "\" ";
                if (($this->getAttribute((isset($context["year"]) ? $context["year"] : null), "value") == (isset($context["n"]) ? $context["n"] : null))) {
                    echo "selected";
                }
                echo ">";
                echo (isset($context["n"]) ? $context["n"] : null);
                echo "</option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['n'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 97
            echo "    </select>
    ";
            // line 98
            echo AppLocal::getString("年");
            echo "

    <select name=\"";
            // line 100
            echo $this->getAttribute((isset($context["month"]) ? $context["month"] : null), "name");
            echo "\">
        <option value=\"\">";
            // line 101
            echo AppLocal::getString("请选择");
            echo "</option>
        ";
            // line 102
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, 12));
            foreach ($context['_seq'] as $context["_key"] => $context["n"]) {
                // line 103
                echo "        <option value=\"";
                echo (isset($context["n"]) ? $context["n"] : null);
                echo "\" ";
                if (($this->getAttribute((isset($context["month"]) ? $context["month"] : null), "value") == (isset($context["n"]) ? $context["n"] : null))) {
                    echo "selected";
                }
                echo ">";
                echo (isset($context["n"]) ? $context["n"] : null);
                echo "</option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['n'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 105
            echo "    </select>
    ";
            // line 106
            echo AppLocal::getString("月");
            echo "
    <select name=\"";
            // line 107
            echo $this->getAttribute((isset($context["day"]) ? $context["day"] : null), "name");
            echo "\">
        <option value=\"\">";
            // line 108
            echo AppLocal::getString("请选择");
            echo "</option>
        ";
            // line 110
            echo "        ";
            if (twig_in_filter((isset($context["month"]) ? $context["month"] : null), array(0 => 1, 1 => 3, 2 => 5, 3 => 7, 4 => 8, 5 => 10, 6 => 12))) {
                // line 111
                echo "            ";
                $context["days"] = 31;
                // line 112
                echo "        ";
            } elseif (((isset($context["month"]) ? $context["month"] : null) == 2)) {
                // line 113
                echo "            ";
                if (((($this->getAttribute((isset($context["year"]) ? $context["year"] : null), "value") % 400) != 0) && (($this->getAttribute((isset($context["year"]) ? $context["year"] : null), "value") % 4) == 0))) {
                    // line 114
                    echo "                ";
                    $context["days"] = 29;
                    // line 115
                    echo "            ";
                } else {
                    // line 116
                    echo "                ";
                    $context["days"] = 28;
                    // line 117
                    echo "            ";
                }
                // line 118
                echo "        ";
            } else {
                // line 119
                echo "            ";
                $context["days"] = 30;
                // line 120
                echo "        ";
            }
            // line 121
            echo "        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["days"]) ? $context["days"] : null)));
            foreach ($context['_seq'] as $context["_key"] => $context["n"]) {
                // line 122
                echo "        <option value=\"";
                echo (isset($context["n"]) ? $context["n"] : null);
                echo "\" ";
                if (($this->getAttribute((isset($context["day"]) ? $context["day"] : null), "value") == (isset($context["n"]) ? $context["n"] : null))) {
                    echo "selected";
                }
                echo ">";
                echo (isset($context["n"]) ? $context["n"] : null);
                echo "</option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['n'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 124
            echo "    </select>
    ";
            // line 125
            echo AppLocal::getString("日");
            echo "
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 130
    public function gettime($_time = null)
    {
        $context = $this->env->mergeGlobals(array(
            "time" => $_time,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 131
            echo "    ";
            if (call_user_func_array($this->env->getFilter('php_is_object')->getCallable(), array("is_object", (isset($context["time"]) ? $context["time"] : null)))) {
                // line 132
                echo "        ";
                $context["time"] = $this->getAttribute((isset($context["time"]) ? $context["time"] : null), "sec");
                // line 133
                echo "    ";
            }
            // line 134
            echo "    ";
            $context["now"] = call_user_func_array($this->env->getFilter('php_strtotime')->getCallable(), array("strtotime", "now"));
            // line 135
            echo "    ";
            $context["nowYear"] = twig_date_format_filter($this->env, "now", "Y");
            // line 136
            echo "    ";
            $context["timeYear"] = twig_date_format_filter($this->env, (isset($context["time"]) ? $context["time"] : null), "Y");
            // line 137
            echo "    ";
            $context["dis"] = ((isset($context["now"]) ? $context["now"] : null) - (isset($context["time"]) ? $context["time"] : null));
            // line 138
            echo "
    ";
            // line 139
            if (((isset($context["dis"]) ? $context["dis"] : null) < (60 * 60))) {
                // line 140
                echo "        ";
                echo AppLocal::getString("#[num]分钟之前", array("num" => call_user_func_array($this->env->getFilter('php_ceil')->getCallable(), array("ceil", (((isset($context["dis"]) ? $context["dis"] : null) / 60) + 1)))));
                echo "
    ";
            } elseif (((isset($context["dis"]) ? $context["dis"] : null) < ((8 * 60) * 60))) {
                // line 142
                echo "        ";
                echo AppLocal::getString("#[num]小时之前", array("num" => call_user_func_array($this->env->getFilter('php_ceil')->getCallable(), array("ceil", ((((isset($context["dis"]) ? $context["dis"] : null) / 60) / 60) + 1)))));
                echo "
    ";
            } elseif (((isset($context["nowYear"]) ? $context["nowYear"] : null) != (isset($context["timeYear"]) ? $context["timeYear"] : null))) {
                // line 144
                echo "        ";
                echo call_user_func_array($this->env->getFilter('php_date')->getCallable(), array("date", "Y年n月j日 G:i", (isset($context["time"]) ? $context["time"] : null)));
                echo "
    ";
            } else {
                // line 146
                echo "        ";
                echo call_user_func_array($this->env->getFilter('php_date')->getCallable(), array("date", "n月j日 G:i", (isset($context["time"]) ? $context["time"] : null)));
                echo "
    ";
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "base/macro.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  613 => 146,  607 => 144,  601 => 142,  595 => 140,  593 => 139,  590 => 138,  587 => 137,  584 => 136,  581 => 135,  578 => 134,  575 => 133,  572 => 132,  569 => 131,  558 => 130,  545 => 125,  542 => 124,  527 => 122,  522 => 121,  519 => 120,  516 => 119,  513 => 118,  510 => 117,  507 => 116,  504 => 115,  501 => 114,  498 => 113,  495 => 112,  492 => 111,  489 => 110,  485 => 108,  481 => 107,  477 => 106,  474 => 105,  459 => 103,  455 => 102,  451 => 101,  447 => 100,  442 => 98,  439 => 97,  424 => 95,  420 => 94,  416 => 93,  412 => 92,  409 => 91,  406 => 90,  403 => 89,  400 => 88,  389 => 87,  376 => 81,  371 => 80,  360 => 79,  345 => 74,  339 => 72,  333 => 70,  330 => 69,  319 => 68,  304 => 62,  299 => 61,  287 => 60,  270 => 51,  256 => 49,  253 => 48,  250 => 47,  247 => 46,  235 => 45,  222 => 40,  217 => 39,  206 => 38,  192 => 33,  186 => 32,  183 => 31,  180 => 30,  177 => 29,  172 => 28,  169 => 27,  157 => 26,  141 => 20,  130 => 19,  118 => 16,  109 => 12,  99 => 11,  94 => 10,  86 => 7,  75 => 6,  73 => 5,  66 => 4,  53 => 3,  47 => 127,  43 => 83,  40 => 77,  31 => 54,  27 => 42,  23 => 35,  37 => 66,  34 => 12,  32 => 11,  30 => 10,  26 => 9,  24 => 8,  21 => 7,  19 => 23,);
    }
}
