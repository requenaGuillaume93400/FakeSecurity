<?php

class Database
{

    // private const DB_HOST = 'db.3wa.io';
    // private const DB_NAME = 'guillaumereq_projet';
    // private const DB_USER = 'guillaumereq';
    // private const DB_PASSWORD = 'b3af70fcb7d8c05fa7fe252a919a8a2f';

    private const DB_HOST = 'localhost';
    private const DB_NAME = 'conception';
    private const DB_USER = 'root';
    private const DB_PASSWORD = '';

    private static $instance;

    // Get a Pdo connexion (with singleton pattern to prevent useless multiple connexions)
    public static function getPdo() {

        if(self::$instance === null){
            try{
                self::$instance = new PDO('mysql:host='.self::DB_HOST.';dbname='.self::DB_NAME.';charset=utf8', self::DB_USER, self::DB_PASSWORD, 
                                    [
                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                                    ]);
            }catch(PDOException $e){
                echo '<p>
                        Une erreur est survenue, veuillez ré essayer plus tard. Si l\'erreur persiste et que vous souhaitez nous en informer, 
                        copiez collez le message ci-dessous et envoyez le à FakeSecurity@contact.fr
                      </p>
                      <p>Erreur : ' .utf8_encode($e->getMessage()). '</p>
                      <p><a href="homepage" title="Retour sur le site FakeSecurity">Retour sur FakeSecurity</a></p>';
                exit;
            }
        }

        return self::$instance;

    }                        

}