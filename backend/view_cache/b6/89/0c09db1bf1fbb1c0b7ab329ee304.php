<?php

/* login/index.twig */
class __TwigTemplate_b6890c09db1bf1fbb1c0b7ab329ee304 extends Twig_Template
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
        echo "<html>
<head>
  <meta charset=\"utf-8\">
  <title>鲜旅客后台管理</title>
</head>
<style type=\"text/css\">
    table{
        padding-top: 5%;;
    }
    td{
        float: left;
        padding-top: 20px;
        padding-left: 10px;;
        font-size: 20px;
    }
    input{
        width:200px;
        font-size: 20px;
    }
    .error-message{
        font-size: 18px;
        color: red;
    }
</style>
<body>
    <form method=\"POST\">
        <table align=\"center\">
            <tr>
                <td>登陆账号:</td>
                <td><input name='User[name]'></td>
            </tr>
            <tr>
                <td>登陆账号:</td>
                <td><input name='User[password]' type=\"password\"></td>
            </tr>
            <tr>
                <td colspan=\"2\">
                    ";
        // line 39
        echo "                    ";
        if ((isset($context["errorMessage"]) ? $context["errorMessage"] : null)) {
            // line 40
            echo "                        <div class='error-message'>";
            echo (isset($context["errorMessage"]) ? $context["errorMessage"] : null);
            echo "</div>
                    ";
        }
        // line 42
        echo "                </td>
            </tr>
            <tr>
                <td colspan=\"2\" align=\"center\">
                    <input type=\"submit\" value=\"登陆\">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "login/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  67 => 42,  61 => 40,  58 => 39,  19 => 1,);
    }
}
