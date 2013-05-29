<?php
class Twig_Extension_Render extends Twig_Extension{
    public function getFunctions(){
        return array(
            new Twig_SimpleFunction('lipsum', 'generate_lipsum'),
        );
    }
}