<?php

class Session
{

    // Get session id of user (to show it in PDF and Mail), @return int or string
    public static function showId()
    {
        if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
            return $_SESSION['id'];
        }
        return 'indéfini';
    }


    // Get session id of user (to show it in PDF and Mail), @return string
    public static function showStatut(): String
    {
        if(isset($_SESSION['statut']) && !empty($_SESSION['statut'])){
            return $_SESSION['statut'];
        }
        return '';
    }


    // Initialize $_SESSION['id'] & $_SESSION['statut'], @return array
    public static function initSession(): array
    {
        $user = [];

        if(\Access::connected()){
            $user['id'] = $_SESSION['id'];
            $user['statut'] = $_SESSION['statut'];
        }else{
            $user['id'] = 'undefined';
            $user['statut'] = 'undefined';
        }
        return $user;
    }

}