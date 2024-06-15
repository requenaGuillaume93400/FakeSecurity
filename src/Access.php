<?php

class Access
{

    // Verify if you are connected (to give access to your account etc...), @return boolean
    public static function connected(): bool
    {
        if(isset($_SESSION) && !empty($_SESSION) && array_key_exists('id', $_SESSION) && array_key_exists('statut', $_SESSION) ){
            return true;
        }
        return false;
    }


    // Verify if you have admin rights on needed pages, if not you get disconnected and you get a message, @return void
    public static function admin(): void
    {
        if(!self::connected() || !isset($_SESSION['admin']) || $_SESSION['admin'] !== 1){
            $_SESSION['fail'] = 1;
            \Http::redirect('deconnexion');
        }
    }


    // Redirect if you aren't connected, @return void
    public static function redirectIfNotConnected(String $toThisPage): void
    {
        if(!\Access::connected()){
            \Http::redirect($toThisPage);
        }
    }


    // Redirect if you aren't client, @return void
    public static function redirectIfNotClient(String $toThisPage): void
    {
        if(!self::connected() || $_SESSION['statut'] !== 'client'){
            \Http::redirect($toThisPage);
        }
    }


    // Redirect if you aren't agent, @return void
    public static function redirectIfNotAgent(String $toThisPage): void
    {
        if(!self::connected() || $_SESSION['statut'] !== 'agent'){
            \Http::redirect($toThisPage);
        }
    }

}