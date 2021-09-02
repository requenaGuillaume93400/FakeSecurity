<?php

class Renderer
{

    // Render the view/template, @return void
    public static function render(string $path, array $variables = []): void{

        // Extract all variables from array $variables so they'll be accessible and usable in phtml template file
        extract($variables); 

        // Transfer all the choosen template code in a variable $pageContent
        ob_start();
        require('src/templates/'. $path .'.phtml');
        $pageContent = ob_get_clean();

        // Require common layout (layout contain header, $pageContent, footer, modals etc...)
        require('src/templates/layout.phtml');

    }

}