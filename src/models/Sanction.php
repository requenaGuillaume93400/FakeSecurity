<?php

namespace Models;

require_once 'src/autoload.php';

class Sanction extends Model
{
  
    protected $table = self::T_SANCTIONS_AGENTS;
    private $sanctionsList = self::T_SANCTIONS;


    // Get sanction of one member to show in Modify.php, @return array or boolean
    public function getData(int $id)
    {            
        $query = $this->pdo->prepare("SELECT
                                        dateSummon, dateNotification, entitled, reason
                                      FROM 
                                        {$this->table}
                                      INNER JOIN 
                                        {$this->sanctionsList}
                                      ON
                                        {$this->table}.idSanction = {$this->sanctionsList}.id AND {$this->table}.idAgent = :id
                                    ");

        $query->execute([':id' => $id]);
        $results = $query->fetchAll();
        return $results;
    }


    // Get all sanctions inflicted to agents to show them in CRUD, @return array or boolean   
    public function getAll()
    {            
        $query = $this->pdo->prepare("SELECT
                                        {$this->table}.id, idAgent, idSanction, entitled, reason, dateNotification
                                      FROM 
                                        {$this->table}
                                      INNER JOIN 
                                        {$this->sanctionsList}
                                      ON
                                      {$this->sanctionsList}.id = {$this->table}.idSanction
                                    ");

        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }


    // Insert a new sanction, @return void
    public function new(int $idAgent, int $idSanction, String $reason, String $dateSummon, String $dateNotification): void
    {            
      $query = $this->pdo->prepare("INSERT INTO 
                                      {$this->table}(idAgent, idSanction, reason, dateSummon, dateNotification) 
                                    VALUES
                                      (:idAgent, :idSanction, :reason, :dateSummon, :dateNotification)
                                  ");

      $query->execute([ ':idAgent' => $idAgent,
                        ':idSanction' => $idSanction,
                        ':reason' => $reason,
                        ':dateSummon' => $dateSummon,
                        ':dateNotification' => $dateNotification
                      ]);
    }

}