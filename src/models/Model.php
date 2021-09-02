<?php

namespace Models;

require_once 'src/tables.php';

use PDO;

abstract class Model
{   

    protected $pdo;
    protected $table;

    protected const T_CLIENTS = CLIENTS;
    protected const T_AGENTS = AGENTS;
    protected const T_AGENTS_CLIENTS = AGENTS_CLIENTS;
    protected const T_ORDERS_DETAILS = ORDERS_DETAILS;
    protected const T_SANCTIONS = SANCTIONS;
    protected const T_SANCTIONS_AGENTS = SANCTIONS_AGENTS;

    public function __construct() {

        $this->pdo = \Database::getPDO();
        
    }

/*************************************************************************************************
 ********************************* Connexion.php & Subscribe.php *********************************
 *************************************************************************************************/

    // Get users's logs (id, mail, password + admin for agents), @return array or bool
    public function getLogs()
    {            
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE mail = :mail LIMIT 0,1");

        // String $statut = substr($this->table, 0, -1) to remove last letter "s" 
        // Exemple: models/Client.php  $_POST['mail-'.$this->table] = 'clients' &  in controllers/Connexion.php  $_SESSION['statut'] = 'client'
        $query->execute([':mail' => htmlspecialchars($_POST['mail-'.substr($this->table, 0, -1)])]);
        return $query;
    }

/*************************************************************************************************
 ****************************************** Connexion.php ****************************************
 *************************************************************************************************/

    // Update column lastLogin, @return void
    public function setLastLogin(int $id): void
    {            
        $query = $this->pdo->prepare("UPDATE 
                                        {$this->table}
                                      SET
                                        lastLogin = NOW()
                                      WHERE 
                                        id = :id
                                    ");

        $query->execute([':id' => $id]);
    }


/*************************************************************************************************
 *************************** Account.php & Modify.php & Demand.php *******************************
 *************************************************************************************************/

    // Get user's data to show in profil account (rewritted in Order.php & Sanction.php), @return array or boolean
    public function getData(int $id) //: array
    {            
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 0,1");

        $query->execute([':id' => $id]);
        $result = $query->fetch();
        // if(!$result){return [];}
        return $result;
    }

/*************************************************************************************************
 *********************************** Account.php & Modify.php ************************************
 *************************************************************************************************/

    // Update data user from his own account page, @return void
    public function update(int $id, String $column, String $newValue): void
    {            
        $query = $this->pdo->prepare("UPDATE 
                                        {$this->table}
                                      SET
                                        $column = :newValue
                                      WHERE 
                                        id = :id
                                    ");

        $query->execute([':id' => $id, ':newValue' => $newValue]);
    }

/*************************************************************************************************
 ***************************************** Account.php & Subscribe.php ***************************
 *************************************************************************************************/

    // Get mail to verif if mail already exist before edit profil, @return array or boolean
    public function getMail(){
      $query = $this->pdo->prepare("SELECT mail FROM {$this->table} WHERE mail = :mail LIMIT 0,1");

      // $_POST['mail-'.substr($this->table, 0, -1)]) == 'client' or 'agent'
      $query->execute([':mail' => htmlspecialchars($_POST['mail-'.substr($this->table, 0, -1)])]);
      $result = $query->fetch();
      return $result;

	}

/*************************************************************************************************
 ***************************************** BackOffice.php ****************************************
 *************************************************************************************************/

    // Get all clients to show them in CRUD (rewritted in Order.php & Sanction.php), @return array or boolean   
    public function getAll()
    {            
        $query = $this->pdo->prepare("SELECT * FROM {$this->table}");

        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }


    // Delete an user, @return void
    public function delete(int $id): void
    {            
      $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
      $query->execute([':id' => $id]);
    }


    // Confirm an user, @return void
    public function confirm(int $id): void
    {            
      $query = $this->pdo->prepare("UPDATE {$this->table} SET confirmed = 1 WHERE id = :id");
      $query->execute([':id' => $id]);
    }

}