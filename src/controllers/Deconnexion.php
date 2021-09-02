<?php

namespace Controllers;

require_once 'src/autoload.php';

class Deconnexion
{

    public function run(): void
    {

        session_start();
    
        // Define a variable that will survive to session destroy to know if user did something wrong
        if(isset($_SESSION['fail']) && $_SESSION['fail'] === 1){
            $notification = 1;
        }

        // Get notification code if user delete his account
        if(isset($_GET['success']) && intval($_GET['success']) === 2){
            $notification = 2;
        }
    
        $_SESSION = [];
        session_destroy();
    
        // If $notification exist, redirect with message, else redirect only
        if(isset($notification) && $notification === 1){
            \Http::redirect("connexion-fail-$notification");          
        }elseif(isset($notification) && $notification === 2){
            \Http::redirect("connexion-success-$notification");
        }else{
            \Http::redirect('connexion');
        }

    }

}