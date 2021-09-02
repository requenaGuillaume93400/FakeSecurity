<?php

namespace Controllers;

require_once 'src/autoload.php';

class Connexion extends Controllers
{

    // Run main script
    public function run(): void
    {

        session_start();    

        if(\Access::connected()){
            \Http::redirect("account-$_SESSION[statut]-$_SESSION[id]");
        }

        // Errors List - $dataStatut[$notificationCode] to read the error
        $dataStatut = [
            200 => '', // Success
            1 => 'Le champ doit être complété',
            2 => 'Ce mail n\'existe pas chez nous',
            3 => 'Le mot de passe est incorrect',
        ];

        $notif = new \Notification();

        // Set Notification Message for success and fail
        $successMessages = ['Vous avez été inscrit avec succès ! Vous pouvez désormais vous connecter.', 'Votre compte à bien été supprimé, byebye.'];
        $notifSuccess = $notif->setSuccess($successMessages);
        $notifFail = $notif->setFail('Vous avez tenté d\'accéder à un espace interdit, vous avez été géocalisé <span id="loc"></span>, notre équipe arrive pour vous casser les jambes, veuillez patienter...');
                        
        // Set Notification code
        $notifMa = $notif->setNotif('agent-mail');
        $notifPa = $notif->setNotif('agent-p1');
        $notifMc = $notif->setNotif('client-mail');
        $notifPc = $notif->setNotif('client-p1');

        // Verify data and log user if everything's alright
        $this->submit('agent');
        $this->submit('client');

        $pageTitle = 'Connexion';

        \Renderer::render('connexion', compact('pageTitle', 'notifSuccess', 'notifFail', 'dataStatut', 'notifMc', 'notifPc', 'notifMa', 'notifPa'));

    }


    // Submit logs to connect the user
    private function submit(string $statut): void
    {

        if(isset($_POST['submit-'.$statut])){
    
            if(isset($_POST['mail-'.$statut]) && !empty($_POST['mail-'.$statut])){     

                if($statut === 'client'){

                    $client = new \Models\Client();
                    $query = $client->getLogs();

                }elseif($statut === 'agent'){

                    $agent = new \Models\Agent();
                    $query = $agent->getLogs();

                }

                $result = $query->fetch();

                if(!$result){
                    $mailStatut = 2; // Mail doesn't exist
                }

            }else{
                $mailStatut = 1; // Empty field
                $result = false;
            }
    
            if(!isset($_POST['pass-'.$statut]) || empty($_POST['pass-'.$statut])){      
                $passwordStatut = 1;  // Empty field
            }

            if(isset($result) && $result){

                $mailStatut = $this->mailLogs($result, $statut);
                $passwordStatut = $this->passwordLogs($result, $statut);
        
                // If mail & password are correct, create session and redirect the user to his profil page
                if($mailStatut === 200 && $passwordStatut === 200){

                    $id = intval($result['id']);
                    $_SESSION['id'] = $id;
                    $_SESSION['statut'] = htmlspecialchars($statut);
                    $_SESSION['mail'] = htmlspecialchars($result['mail']);

                    // Only agent can be admin
                    if($statut === 'agent'){
                        $_SESSION['admin'] = intval($result['admin']);
                    }

                    // Update column LastLogin
                    if($statut === 'client'){
                        $client->setLastLogin($id);
                    }elseif($statut === 'agent'){
                        $agent->setLastLogin($id);
                    }

                    \Http::redirect("account-$statut-$id");

                }else{
                    \Http::redirect("connexion-$statut-$passwordStatut-$mailStatut");
                }     
            }else{
                \Http::redirect("connexion-$statut-$mailStatut");
            }
        }  
    }


    // Verify if password matches to allow connection or not
    private function passwordLogs($result, $statut)
    {
        if(isset($_POST['pass-'.$statut])){

            if(password_verify($_POST['pass-'.$statut], $result['password'])){
                return 200; // Success
            }         
            return 3; // Incorrect password
        }
        return 1; // Field is empty
    }


    // Verify if mail exist in database
    private function mailLogs($result, $statut)
    {
        if(isset($_POST['mail-'.$statut])){
            if(!$result['mail']){
                return 2; // Mail does not exist
            }
            return 200; // SUCCESS
        }
        return 1; // Field is empty
    }

}