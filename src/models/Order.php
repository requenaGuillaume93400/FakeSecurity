<?php

namespace Models;

require_once 'src/autoload.php';

class Order extends Model
{

    protected $table = self::T_ORDERS_DETAILS;

    // Insert a new order, @return void
    public function new(int $idClient, int $cds, int $cdp, int $acdp, int $opv, int $ads, 
                        int $accd, $beginDate, $endDate, float $priceTTC
                       ): void
    {            
      $query = $this->pdo->prepare("INSERT INTO 
                                      {$this->table}(idClient, cds, cdp, acdp, opv, ads, accd, beginDate, endDate, priceTTC) 
                                    VALUES
                                      (:idClient, :cds, :cdp, :acdp, :opv, :ads, :accd, :beginDate, :endDate, :priceTTC)
                                  ");

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


    // Get All Orders of All members to show them in BackOffice.php, @return array or boolean
    public function getAll()
    {            
      $query = $this->pdo->prepare("SELECT
                                      id, idClient, beginDate, endDate, priceTTC, 
                                      (cds + cdp + ads + opv + accd + acdp) AS totalHours
                                    FROM
                                      {$this->table}
                                    ORDER BY beginDate DESC
                                  ");
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }


    // Get All orders of one member to show in Modify.php
    public function getData(int $id) //: array
    {            
        $query = $this->pdo->prepare("SELECT 
                                        id, idClient, beginDate, endDate, priceTTC, 
                                        (cds + cdp + ads + opv + accd + acdp) AS totalHours 
                                      FROM 
                                        {$this->table} 
                                      WHERE 
                                        idClient = :id
                                    ");

        $query->execute([':id' => $id]);
        $result = $query->fetchAll();
        // if(!$result){return [];}
        return $result;
    }

}