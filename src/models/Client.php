<?php

namespace Models;

require_once 'src/autoload.php';

class Client extends Model
{

    protected $table = self::T_CLIENTS;

/*************************************************************************************************
 **************************************** Subscribe.php ******************************************
 *************************************************************************************************/

    // Insert a new client, rewrite of new() from Model (add $society), @return void
    public function new(String $society, String $lastName, String $firstName, String $mail, String $phone, String $password): void
    {      
        $query = $this->pdo->prepare("INSERT INTO 
                                        {$this->table}(society, lastName, firstName, mail, phone, password) 
                                      VALUES
                                        (:society, :lastName, :firstName, :mail, :phone, :password)
                                    ");

        $query->execute([':society' => $society,
                         ':lastName' => $lastName, 
                         ':firstName' => $firstName, 
                         ':mail' => $mail, 
                         ':phone' => $phone,
                         ':password' => $password
                        ]); 
    }

/*************************************************************************************************
 *********************************** Account.php & Modify.php ************************************
 *************************************************************************************************/

    // Get client's orders data to show in his profil account, @return array or boolean
    public function getOrders(int $id)
    {
        $query = $this->pdo->prepare('SELECT
                                        '.self::T_ORDERS_DETAILS.'.id, priceTTC, beginDate, endDate, 
                                        (cds + cdp + acdp + opv + ads + accd) AS totalHours
                                      FROM 
                                        '.self::T_ORDERS_DETAILS.'
                                      INNER JOIN 
                                        '.self::T_CLIENTS.'
                                      ON
                                        '.self::T_CLIENTS.'.id = '.self::T_ORDERS_DETAILS.'.idClient AND '.self::T_ORDERS_DETAILS.'.idClient = :id
                                    ');

        $query->execute([':id' => $id]);
        $results = $query->fetchAll();
        return $results;
    }


/*************************************************************************************************
 ***************************************** BackOffice.php ****************************************
 *************************************************************************************************/

    // Get all orders to show them in CRUD, @return array or boolean
    public function getAllOrders()
    {            
        $query = $this->pdo->prepare('SELECT
                                        id, idClient, beginDate, endDate, priceTTC, 
                                        (cds + cdp + ads + opv + accd + acdp) AS totalHours
                                      FROM
                                        '.self::T_ORDERS_DETAILS.'
                                      ORDER BY beginDate DESC
                                    ');
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }


/*************************************************************************************************
 ***************************************** Order.php *********************************************
 *************************************************************************************************/

    // Insert a new order, @return void
    public function insertOrder(int $idClient, int $cds, int $cdp, int $acdp, int $opv, int $ads, 
                                int $accd, $beginDate, $endDate, float $priceTTC
                               ): void
    {            
        $query = $this->pdo->prepare('INSERT INTO 
                                        '.self::T_ORDERS_DETAILS.'
                                        (idClient, cds, cdp, acdp, opv, ads, accd, beginDate, endDate, priceTTC) 
                                     VALUES
                                        (:idClient, :cds, :cdp, :acdp, :opv, :ads, :accd, :beginDate, :endDate, :priceTTC)
                                    ');
                    
        $query->execute([':idClient' => $idClient,
                         ':cds' => $cds, 
                         ':cdp' => $cdp, 
                         ':acdp' => $acdp,
                         ':opv' => $opv,
                         ':ads' => $ads,
                         ':accd' => $accd,
                         ':beginDate' => $beginDate,
                         ':endDate' => $endDate,
                         ':priceTTC' => $priceTTC
                        ]);
    }

}