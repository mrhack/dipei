<?php
class App_Twig_Extension extends Twig_Extension{
    public function getFunctions(){
        return array(
            new Twig_SimpleFunction('lipsum', 'generate_lipsum'),
        );
    }

    // ...
}