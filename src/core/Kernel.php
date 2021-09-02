<?php

namespace Core;

require_once './src/autoload.php';

class Kernel
{

    public function process(){

        $router = new \Core\Router();
        $router->route();

    }

}