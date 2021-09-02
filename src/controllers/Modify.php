<?php

namespace Controllers; 

require_once 'src/autoload.php';

class Modify extends Update
{

    public function run(): void
    {
        
        session_start();

        $user = \Session::initSession();
        $id = $user['id'];
        $statut = $user['statut'];

        // Verify if you have admin rights, if not -> disconnect you and show a message
        \Access::admin();

        // $authorizedStatus = ['client', 'agent'];
        $authorizedStatus = $this->authorizedStatus;

        if(isset($_GET['statut']) && in_array($_GET['statut'], $authorizedStatus) 
         && isset($_GET['id']) && $_GET['id'] > 0){

            $memberStatut = htmlspecialchars($_GET['statut']);
            $memberId = intval($_GET['id']);

            // Get data agent or client in an array $result
            $result = $this->getDataUser($memberStatut, $memberId);

            // If result is empty -> disconnect user, he should use CRUD not URL
            if($result === [] || !$result){      
                $_SESSION['fail'] = 1;
                \Http::redirect('deconnexion');
            }

            $orders = null;
            $sanctions = null;

            switch($memberStatut){
                case 'client':
                    $order = new \Models\Order();
                    $orders = $order->getData($memberId);
                    break;
                
                case 'agent':
                    $sanction = new \Models\Sanction();
                    $sanctions = $sanction->getData($memberId);
                    break;
            }

            // Secure and insert new values to update database
            $this->member($memberStatut, $result, $memberId);
            
        }else{
            $_SESSION['fail'] = 1;
            \Http::redirect('deconnexion');
        }

        $pageTitle = 'Edit Member';

        \Renderer::render('modify', compact('id', 'statut', 'pageTitle', 'memberStatut', 'authorizedStatus', 'result', 'memberId', 'orders', 'sanctions'));
    
    }


    // Update selected member profil, @return void
    private function member(String $memberStatut, array $result, int $memberId): void
    {

        if(isset($_POST['submit-edit-'.$memberStatut]) && in_array($memberStatut, $this->authorizedStatus)){

            if($memberStatut === 'client'){

                // List of authorized values for <select name='type'> in quotation.phtml
                $authorizedSiteType = ['alimentaire', 'bricolage', 'culturel', 'bijouterie', 'gare', 'aeroport', 'concert', 'sport', 'autre'];

                $societyStatut = $this->optionnalData('society', self::REGEX_SOCIETY, 2, 50);
                $siteNameStatut = $this->data('site-name', self::REGEX_TEXT, 2, 50);
                $siteTypeStatut = $this->type($authorizedSiteType, 'type');
                $superficieStatut = $this->number('superficie');    

                // Secure data and update data in database
                $this->launchUpdate($societyStatut, 'society', 'society', $result, $memberId, $memberStatut.'s');
                $this->launchUpdate($siteNameStatut, 'site-name', 'siteName', $result, $memberId, $memberStatut.'s');
                $this->launchUpdate($siteTypeStatut, 'type', 'siteType', $result, $memberId, $memberStatut.'s');
                $this->launchUpdate($superficieStatut, 'superficie', 'superficie', $result, $memberId, $memberStatut.'s');

            }

            if($memberStatut === 'agent'){

                $matriculeStatut = $this->data('matricule', self::REGEX_MATRICULE, 8, 14);
                $gradeStatut = $this->data('grade', self::REGEX_TEXT, 1, 25);

                $this->launchUpdate($matriculeStatut, 'matricule', 'matricule', $result, $memberId, $memberStatut.'s');
                $this->launchUpdate($gradeStatut, 'grade', 'grade', $result, $memberId, $memberStatut.'s');

            }

            // Common to both status : agent & client
            $lastNameStatut = $this->data('last-name-'.$memberStatut, self::REGEX_TEXT, 2, 50);
            $firstNameStatut = $this->data('first-name-'.$memberStatut, self::REGEX_TEXT, 2, 50);
            $mailStatut = $this->data('mail-'.$memberStatut, self::REGEX_MAIL, 2, 50);
            $phoneStatut = $this->data('phone-'.$memberStatut, self::REGEX_PHONE, 9, 13);
            $passwordStatut = $this->password($memberStatut);
            $passwordMatchesStatut = $this->passwordMatches($memberStatut);
            $addressStatut = $this->data('address', self::REGEX_ADDRESS, 2, 150);
            $postalCodeStatut = $this->data('postal', self::REGEX_POSTAL, 2, 8);
            $cityStatut = $this->data('city', self::REGEX_TEXT, 2, 50);
            $countryStatut = $this->data('country', self::REGEX_TEXT, 2, 50);            

            // Secure data and update data in database
            $this->launchUpdate($addressStatut, 'address', 'address', $result, $memberId, $memberStatut.'s');
            $this->launchUpdate($postalCodeStatut, 'postal', 'postalCode', $result, $memberId, $memberStatut.'s');
            $this->launchUpdate($cityStatut, 'city', 'city', $result, $memberId, $memberStatut.'s');
            $this->launchUpdate($countryStatut, 'country', 'country', $result, $memberId, $memberStatut.'s');
            $this->launchUpdate($phoneStatut, 'phone-'.$memberStatut, 'phone', $result, $memberId, $memberStatut.'s');
            $this->launchUpdate($mailStatut, 'mail-'.$memberStatut, 'mail', $result, $memberId, $memberStatut.'s');
            $this->launchUpdate($lastNameStatut, 'last-name-'.$memberStatut, 'lastName', $result, $memberId, $memberStatut.'s');
            $this->launchUpdate($firstNameStatut, 'first-name-'.$memberStatut, 'firstName', $result, $memberId, $memberStatut.'s');

            if($passwordStatut === 200){
                if($passwordMatchesStatut === 200){
                    $this->newPassword('pass-'.$memberStatut, 'pass-verif-'.$memberStatut, 'password', $result, $memberId, $memberStatut.'s');
                }
            }
        }

    }


    // Get data of an user, @return array or bool
    private function getDataUser(String $memberStatut, int $memberId)
    {
        switch($memberStatut){
            case 'client':
                $client = new \Models\Client();
                $result = $client->getData($memberId);
                return $result;
    
            case 'agent':
                $agent = new \Models\Agent();
                $result = $agent->getData($memberId);
                return $result;
        }
    }

}