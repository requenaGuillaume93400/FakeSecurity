<?php

namespace Controllers;

require_once 'src/autoload.php';

// Extends Modify.php & Account.php
abstract class Update extends Controllers
{

    protected $authorizedStatus = ['client', 'agent'];
    protected $authorizedTables = ['clients', 'agents'];

    protected $AgentsColumns =  [
                                 'id', 'matricule', 'grade', 'lastName', 'firstName',
                                 'mail', 'phone', 'password', 'address', 'postalCode', 'city', 'country'
                                ];

    protected $ClientsColumns = [
                                 'id', 'society', 'siteName', 'siteType', 'superficie', 'lastName', 'firstName',
                                 'mail', 'phone', 'password', 'address', 'postalCode', 'city', 'country'
                                ];


    // Verify and secure new data, if data is the same than in database, do nothing, else update, @return void
    private function infos(String $input, String $column, array $result, int $id, String $table): void
    {        
        if(isset($_POST[$input]) && !empty($_POST[$input]) && $_POST[$input] !== $result[$column]){

            if(is_numeric($_POST[$input])){
                $newValue = intval($_POST[$input]);
            }else{
                $newValue = htmlspecialchars($_POST[$input]);
            }

            if($table === 'clients'){
                $client = new \Models\Client();
                $client->update($id, $column, $newValue);
            }

            if($table === 'agents'){
                $agent = new \Models\Agent();
                $agent->update($id, $column, $newValue);
            }

        }
    }


    // Verify and secure new password, if the pass is already in database, do nothing, else update, @return void
    protected function newPassword(String $input, String $verifInput, String $column, array $result, 
                                    int $id, String $table
                                   ): void
    {
        if(in_array($column, $this->ClientsColumns) || in_array($column, $this->AgentsColumns)){

            if(isset($_POST[$input]) && !empty($_POST[$input]) 
                && !password_verify($_POST[$input], $result[$column]) 
                && in_array($table, $this->authorizedTables)
            ){
                
                if($_POST[$input] === $_POST[$verifInput]){
    
                    // Secure data then hash password
                    $newValue = htmlspecialchars($_POST[$input]);
                    $newValue = password_hash($newValue, PASSWORD_DEFAULT);
    
                    if($table === 'clients'){
                        $client = new \Models\Client();
                        $client->update($id, $column, $newValue);
                    }
    
                    if($table === 'agents'){
                        $agent = new \Models\Agent();
                        $agent->update($id, $column, $newValue);
                    }
    
                }
            }
        }
    }


    // Launch update processus if dataStatut is on Success
    protected function launchUpdate(int $dataStatut, String $input, String $column, array $result, int $id, String $table): void
    {
        if(in_array($column, $this->ClientsColumns) || in_array($column, $this->AgentsColumns)){
            if($dataStatut === 200 && in_array($table, $this->authorizedTables)){
                $this->infos($input, $column, $result, $id, $table);
            }
        }
    }

}