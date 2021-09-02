<?php

namespace Controllers;

require_once 'src/autoload.php';

// Extends Bonus.php, Rules.php & Quality.php
abstract class MemberArea
{

    protected $page;
    protected $statut;

    public function run(): void
    {

        session_start();

        switch($this->statut){
            case 'client':
                \Access::redirectIfNotClient('connexion');
                break;

            case 'agent':
                \Access::redirectIfNotAgent('connexion');
                break;
        }

        $id = $_SESSION['id'];
        $statut = $_SESSION['statut'];

        $pageTitle = ucfirst($this->page);

        \Renderer::render($this->page, compact('id', 'statut', 'pageTitle'));

    }

}