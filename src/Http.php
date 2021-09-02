<?php

class Http
{

    // Redirect
    public static function redirect(String $url): void
    {
        header("Location: $url");
        exit;
    }


    // If a non admin try to access to another member page account (or admin pages) by URL, he get disconnected and get a message
    public static function attemptByURL(String $statut, int $id): void
    {
        if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== 1){        
            if(htmlspecialchars($_GET['statut']) !== $statut || intval($_GET['id']) !== $id){ 
                $_SESSION['fail'] = 1;
                Http::redirect('deconnexion');
            }
        }
    }

}