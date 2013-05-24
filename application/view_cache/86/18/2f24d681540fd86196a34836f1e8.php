<?php

/* base/frame.twig */
class __TwigTemplate_86182f24d681540fd86196a34836f1e8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'head' => array($this, 'block_head'),
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"utf-8\" />
        <title>";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
        // line 7
        echo "        ";
        // line 8
        echo "        <!--
        <link rel=\"stylesheet\" href=\"/sta??base.css,a.css,b.css,d.css?_=213213213\" />
        <script style=\"text/javascript\" href=\"/sta??base.js,a.js,b.js,e.js?_=234213123\"></script>
        -->
        ";
        // line 12
        $this->displayBlock('head', $context, $blocks);
        // line 14
        echo "    </head>
    <body>
        <header>
            ";
        // line 17
        $this->displayBlock('header', $context, $blocks);
        // line 18
        echo "        </header>
        <div id=\"content\">";
        // line 19
        $this->displayBlock('content', $context, $blocks);
        echo "</div>
        <footer>
            ";
        // line 21
        $this->displayBlock('footer', $context, $blocks);
        // line 23
        echo "        </footer>
    </body>
</html>";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
    }

    // line 12
    public function block_head($context, array $blocks = array())
    {
        // line 13
        echo "        ";
    }

    // line 17
    public function block_header($context, array $blocks = array())
    {
    }

    // line 19
    public function block_content($context, array $blocks = array())
    {
    }

    // line 21
    public function block_footer($context, array $blocks = array())
    {
        // line 22
        echo "            ";
    }

    public function getTemplateName()
    {
        return "base/frame.twig";
    }

    public function getDebugInfo()
    {
        return array (  92 => 22,  89 => 21,  84 => 19,  79 => 17,  75 => 13,  72 => 12,  67 => 5,  61 => 23,  59 => 21,  54 => 19,  51 => 18,  49 => 17,  44 => 14,  42 => 12,  36 => 8,  34 => 7,  30 => 5,  24 => 1,);
    }
}
