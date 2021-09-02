<?php

namespace Controllers;

require_once 'src/autoload.php';

class Account extends Update
{

    public function run(): void
    {

        session_start();
    
        if(!\Access::connected()){
            \Http::redirect('connexion');
        }
    
        $statut = $_SESSION['statut'];
        $id = $_SESSION['id'];
    
        // If a non admin try to acces to another member page account, he get disconnected and he get a message
        \Http::attemptByURL($statut, $id);

        // Delete account of an user from his personnal account page
        $this->deleteAccount($statut, $id);
    
        $orders = null;
        $sanctions = null;
    
        // Show personnal account informations
        switch($statut){
            case 'client':
                $client = new \Models\Client();
                $result = $client->getData($id);
                $orders = $client->getOrders($id);
                break;
                                
            case 'agent':
                $agent = new \Models\Agent();
                $result = $agent->getData($id);
                $sanctions = $agent->getSanctions($id);
                break;
        }        
    
        $pageTitle = 'Profil';
            
        // Update profil member informations from his own personnal account page
        $this->profil($statut, $result, $id);
    
        \Renderer::render('account', compact('id', 'statut', 'pageTitle', 'result', 'sanctions', 'orders'));
        
    }


    // Verify and insert data to modify account informations from personnal account ====> Resemble bcp a Modify.php ?????? Member
    private function profil(String $statut, $result, int $id): void
    {

        if(isset($_POST['submit-edit-'.$statut]) && in_array($statut, $this->authorizedStatus)){

          if($statut === 'client'){

              // List of authorized values for <select name='type'> in quotation.phtml
              $authorizedSiteType = ['alimentaire', 'bricolage', 'culturel', 'bijouterie', 'gare', 'aeroport', 'concert', 'sport', 'autre'];

              $societyStatut = $this->optionnalData('society', self::REGEX_SOCIETY, 2, 50);
              $siteNameStatut = $this->data('site-name', self::REGEX_TEXT, 2, 50);
              $siteTypeStatut = $this->type($authorizedSiteType, 'type');
              $superficieStatut = $this->number('superficie');    

              // Secure data and update data in database
              $this->launchUpdate($societyStatut, 'society', 'society', $result, $id, $statut.'s');
              $this->launchUpdate($siteNameStatut, 'site-name', 'siteName', $result, $id, $statut.'s');
              $this->launchUpdate($siteTypeStatut, 'type', 'siteType', $result, $id, $statut.'s');
              $this->launchUpdate($superficieStatut, 'superficie', 'superficie', $result, $id, $statut.'s');

            }

            // Common to both status : agent & client
            $lastNameStatut = $this->data('last-name-'.$statut, self::REGEX_TEXT, 2, 50);
            $firstNameStatut = $this->data('first-name-'.$statut, self::REGEX_TEXT, 2, 50);
            $mailStatut = $this->data('mail-'.$statut, self::REGEX_MAIL, 2, 50);
            $phoneStatut = $this->data('phone-'.$statut, self::REGEX_PHONE, 9, 13);
            $passwordStatut = $this->password($statut);
            $passwordMatchesStatut = $this->passwordMatches($statut);
            $addressStatut = $this->data('address', self::REGEX_ADDRESS, 2, 150);
            $postalCodeStatut = $this->data('postal', self::REGEX_POSTAL, 2, 8);
            $cityStatut = $this->data('city', self::REGEX_TEXT, 2, 50);
            $countryStatut = $this->data('country', self::REGEX_TEXT, 2, 50);            

            // Secure data and update data in database
            $this->launchUpdate($addressStatut, 'address', 'address', $result, $id, $statut.'s');
            $this->launchUpdate($postalCodeStatut, 'postal', 'postalCode', $result, $id, $statut.'s');
            $this->launchUpdate($cityStatut, 'city', 'city', $result, $id, $statut.'s');
            $this->launchUpdate($countryStatut, 'country', 'country', $result, $id, $statut.'s');
            $this->launchUpdate($phoneStatut, 'phone-'.$statut, 'phone', $result, $id, $statut.'s');
            $this->launchUpdate($lastNameStatut, 'last-name-'.$statut, 'lastName', $result, $id, $statut.'s');
            $this->launchUpdate($firstNameStatut, 'first-name-'.$statut, 'firstName', $result, $id, $statut.'s');

            $client = new \Models\Client();
            $agent = new \Models\Agent();

            switch($statut){
                case 'client':                    
                    $mailExist = $client->getMail();
                    break;
                
                case 'agent':                    
                    $mailExist = $agent->getMail();
                    break;              
            }            

            // If mail isn't already in database (exemple other members mail), you can try an update
            if(!$mailExist){
                $this->launchUpdate($mailStatut, 'mail-'.$statut, 'mail', $result, $id, $statut.'s');
            }

            if($passwordStatut === 200){
                if($passwordMatchesStatut === 200){
                    $this->newPassword('pass-'.$statut, 'pass-verif-'.$statut, 'password', $result, $id, $statut.'s');
                }
            }
        }
    }


    // Delete account from personnal account page, @return void
    private function deleteAccount(String $statut, int $id): void{

        if(isset($_GET['delete']) && intval($_GET['delete']) === 1){    

            // This will create an Agent or Client object (depend on $statut), from Models namespace
            $user = '/Models/'.ucfirst($statut);
            $user = str_replace('/', '\\', $user);
            $user = new $user();
            $user->delete($id);   

            \Http::redirect('deconnexion-success-2');
        }

        if(isset($_POST['delete-'.$statut])){         
            \Http::redirect("account-$statut-$id-1");
        }        

    }
        

}