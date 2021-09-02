<?php

namespace Controllers;

require_once 'src/autoload.php';

class Subscribe extends Controllers
{

    public function run(): void
    {

        session_start();

        if(\Access::connected()){
            \Http::redirect("account-$_SESSION[statut]-$_SESSION[id]");
        }

        $dataStatut = [
            200 => '', // Success
            1 => 'Le champ doit être complété',
            2 => 'La valeur est trop longue ou trop courte',
            3 => 'Ne respecte pas le format imposé ou contient des caractères interdits',
            4 => 'Les mots de passe ne correspondent pas',
            5 => 'Le mot de passe doit contenir au moins 6 caractères dont 1 minuscule, 1 majuscule, 1 chiffre et un caractère spécial (&.#~+=*\-_+²$=¤)',
            6 => 'Ce mail existe déja'
        ];

        $notif = new \Notification();

        // Set Notifications CODE for agent
        $notifLa = $notif->setNotif('agent-l-name');
        $notifFa = $notif->setNotif('agent-f-name');
        $notifMa = $notif->setNotif('agent-mail');
        $notifPa = $notif->setNotif('agent-phone');
        $notifP1a = $notif->setNotif('agent-p1');
        $notifP2a = $notif->setNotif('agent-p2');

        // Set Notifications CODE for client
        $notifSc = $notif->setNotif('society'); // only for clients
        $notifLc = $notif->setNotif('client-l-name');
        $notifFc = $notif->setNotif('client-f-name');
        $notifMc = $notif->setNotif('client-mail');
        $notifPc = $notif->setNotif('client-phone');
        $notifP1c = $notif->setNotif('client-p1');
        $notifP2c = $notif->setNotif('client-p2');

        // Verify & secure data, then insert into bdd if correct (and user isn't already subscribe)
        $this->submit('agent');
        $this->submit('client');

        $pageTitle = 'Inscription';

        \Renderer::render('subscribe', compact( 'pageTitle', 'dataStatut', 
                                                'notifSc', 'notifLc', 'notifFc', 'notifMc', 'notifPc', 'notifP1c', 'notifP2c',
                                                'notifLa', 'notifFa', 'notifMa', 'notifPa', 'notifP1a', 'notifP2a'
                                              )
                         );
    
    }



    // Submit subscribe form, @return void
    private function submit(string $statut): void
    {
        if(isset($_POST['submit-'.$statut])){
        
            // Verify data and put statut code into variables
            $lastNameStatut = $this->data('last-name-'.$statut, self::REGEX_TEXT, 2, 50);
            $firstNameStatut = $this->data('first-name-'.$statut, self::REGEX_TEXT, 2, 50);
            $mailStatut = $this->data('mail-'.$statut, self::REGEX_MAIL, 2, 50);
            $phoneStatut = $this->data('phone-'.$statut, self::REGEX_PHONE, 9, 13);
            $societyStatut = $this->optionnalData('society', self::REGEX_SOCIETY, 1, 50); // only for clients
            $passwordStatut = $this->password($statut);
            $passwordMatchesStatut = $this->passwordMatches($statut);
            
            $client = new \Models\Client();
            $agent = new \Models\Agent();
        
            // If mail already exist in database, set mailstatut with error 6 
            // switch répété dans Controllers/Account.php
            if($mailStatut === 200){
                switch($statut){
                    case 'client':
                        $mailExist = $client->getMail();
                        break;
                    
                    case 'agent':
                        $mailExist = $agent->getMail();
                        break;              
                }
                
                if($mailExist){
                    $mailStatut = 6;
                }
            }
                
            // If everything is alright => secure data then => insert data into database
            if($lastNameStatut === 200 
                && $firstNameStatut === 200 
                && $mailStatut === 200 
                && $phoneStatut === 200 
                && $passwordStatut === 200 
                && $passwordMatchesStatut === 200
            ){
        
                if($societyStatut === 1 || $societyStatut === 200){
        
                    // 1) Secure data
                    $lastName = htmlspecialchars($_POST['last-name-'.$statut]);
                    $firstName = htmlspecialchars($_POST['first-name-'.$statut]);
                    $mail = htmlspecialchars($_POST['mail-'.$statut]);
                    $mail = filter_var($_POST['mail-'.$statut], FILTER_SANITIZE_EMAIL);
                    $phone = htmlspecialchars($_POST['phone-'.$statut]);
                    $password = trim(strip_tags(htmlspecialchars($_POST['pass-verif-'.$statut])));
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    
                    if($societyStatut === 200){
                        $society = htmlspecialchars($_POST['society']);
                    }else{
                        $society = "NULL";
                    }
            
                    // 2) Insert data     
                    switch($statut){
                        case 'client':
                            $client->new($society, $lastName, $firstName, $mail, $phone, $password);
                            break;
                        
                        case 'agent':
                            $agent->new($lastName, $firstName, $mail, $phone, $password);
                            break;          
                    }                
                }
                \Http::redirect("connexion-success-1");
            }else{
                \Http::redirect("subscribe-$statut-$lastNameStatut-$firstNameStatut-$mailStatut-$phoneStatut-$societyStatut-$passwordStatut-$passwordMatchesStatut");
            }
        }
    }  

}