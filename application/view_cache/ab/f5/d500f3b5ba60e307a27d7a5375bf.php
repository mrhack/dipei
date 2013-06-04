<?php

/* base/frame.twig */
class __TwigTemplate_abf5d500f3b5ba60e307a27d7a5375bf extends Twig_Template
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
        // line 6
        $this->displayBlock('head', $context, $blocks);
        // line 7
        echo "    </head>
    <body>
        <header>
            ";
        // line 10
        $this->displayBlock('header', $context, $blocks);
        // line 11
        echo "        </header>
        <div id=\"content\">";
        // line 12
        $this->displayBlock('content', $context, $blocks);
        echo "</div>
        <footer>
            ";
        // line 14
        $this->displayBlock('footer', $context, $blocks);
        // line 16
        echo "        </footer>
    </body>
</html>";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
    }

    // line 6
    public function block_head($context, array $blocks = array())
    {
    }

    // line 10
    public function block_header($context, array $blocks = array())
    {
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
    }

    // line 14
    public function block_footer($context, array $blocks = array())
    {
        // line 15
        echo "            ";
    }

    public function getTemplateName()
    {
        return "base/frame.twig";
    }

    public function getDebugInfo()
    {
        return array (  82 => 15,  79 => 14,  74 => 12,  69 => 10,  59 => 5,  53 => 16,  51 => 14,  41 => 10,  36 => 7,  34 => 6,  30 => 5,  24 => 1,  71 => 20,  68 => 19,  64 => 6,  58 => 13,  55 => 12,  46 => 12,  43 => 11,  38 => 5,  32 => 3,);
    }
}
