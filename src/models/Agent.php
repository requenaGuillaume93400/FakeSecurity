<?php

namespace Models;

require_once 'src/autoload.php';

class Agent extends Model
{

    protected $table = self::T_AGENTS;

/*************************************************************************************************
 **************************************** Subscribe.php ******************************************
 *************************************************************************************************/

    // Insert a new agent, @return void
    public function new(String $lastName, String $firstName, String $mail, String $phone, String $password): void
    {            
    $query = $this->pdo->prepare("INSERT INTO 
                                    {$this->table}(lastName, firstName, mail, phone, password) 
                                  VALUES
                                    (:lastName, :firstName, :mail, :phone, :password)
                                ");

    $query->execute([':lastName' => $lastName, 
                     ':firstName' => $firstName, 
                     ':mail' => $mail, 
                     ':phone' => $phone,
                     ':password' => $password
                    ]);
    }

/*************************************************************************************************
 *********************************** Account.php & Modify.php ************************************
 *************************************************************************************************/

    // Get agent's sanctions data to show in his profil account, @return array or boolean
    public function getSanctions(int $id)
    {
        $query = $this->pdo->prepare('SELECT
                                        dateSummon, dateNotification, entitled, reason
                                      FROM 
                                        '.self::T_SANCTIONS_AGENTS.'
                                      INNER JOIN 
                                        '.self::T_SANCTIONS.'
                                      ON
                                        '.self::T_SANCTIONS_AGENTS.'.idSanction = '.self::T_SANCTIONS.'.id AND '.self::T_SANCTIONS_AGENTS.'.idAgent = :id
                                    ');
                                    
        $query->execute([':id' => $id]);
        $results = $query->fetchAll();
        return $results;
    }


/*************************************************************************************************
 ***************************************** BackOffice.php ****************************************
 *************************************************************************************************/

    // Give admin rights to an agent, @return void => UNIQUE
    public function setAdmin(int $id): void
    {            
      $query = $this->pdo->prepare("UPDATE {$this->table} SET admin = 1 WHERE id = :id");
      $query->execute([':id' => $id]);
    }

}