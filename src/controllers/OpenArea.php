<?php

namespace Controllers;

require_once 'src/autoload.php';

// Used in Homepage.php & Services.php
abstract class OpenArea
{

    protected $titlePage;
    protected $redirectPage;

    public function run(): void
    {

        session_start();

        $user = \Session::initSession();
        $id = $user['id'];
        $statut = $user['statut'];

        $pageTitle = $this->titlePage;

        \Renderer::render($this->redirectPage, compact('id', 'statut', 'pageTitle'));

    }

}