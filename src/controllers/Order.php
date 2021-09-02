<?php

namespace Controllers;

require_once 'src/autoload.php';

class Order extends Controllers
{

    public function run(): void
    {

        session_start();

        $user = \Session::initSession();
        $id = $user['id'];
        $statut = $user['statut'];

        // Verify if you have admin rights, if not -> disconnect you and show a message
        \Access::admin();

        if(isset($_GET['success']) && intval($_GET['success']) === 2){
            $notifSuccess = "La commande a été ajouté avec succès.";
        }else{
            $notifSuccess = null;
        }

        // Errors list
        $dataStatut = [
            200 => '', // Success
            1 => 'Le champ doit être complété',
            2 => 'La valeur est trop longue ou trop courte',
            3 => 'Ne respecte pas le format imposé ou contient des caractères interdits',
            4 => 'Cette valeur doit être supérieur a 0',
            5 => 'Si vous vous amusez à modifier du html, ça ne va pas le faire...',
            6 => 'Il y à une incohérence dans les dates (date de début supérieure à la date de fin)',
            7 => 'Une erreur figure dans les dates',
            8 => 'Le client n\'existe pas, vous jouer à quoi là ?',
            9 => 'La longueure maximal est dépassée'
        ];


        // Error notification code (GET)
        $notif = new \Notification();
        $notifId = $notif->setOptionnalNotif('id');
        $notifDate = $notif->setNotif('date');
        $notifLh = $notif->setOptionnalNotif('lh');
        $notifTh = $notif->setOptionnalNotif('th');
        $notifSh = $notif->setOptionnalNotif('sh');
        $notifAh = $notif->setNotif('ah');
        $notifVh = $notif->setOptionnalNotif('vh');
        $notifDh = $notif->setOptionnalNotif('dh');
        $notifTtc = $notif->setNotif('ttc');

        // Get list of clients for the select id-client
        $client = new \Models\Client();
        $clients = $client->getAll();

        // Secure data and insert a new order in database
        $this->add();

        $pageTitle = 'Nouvelle commande';

        \Renderer::render('order', compact(
                                            'id', 'statut', 'pageTitle', 'dataStatut', 'clients', 'notifId', 'notifDate', 'notifLh', 
                                            'notifTh', 'notifSh', 'notifAh', 'notifVh', 'notifDh', 'notifTtc', 'notifSuccess'
                                          )
                         );

    }


    // Add a new order, @return void
    private function add(): void
    {

        if(isset($_POST['submit'])){

            // 1) Verify data
                // Required data
            $datesStatut = $this->dates('begin-date', 'end-date');
            $agentHoursStatut = $this->number('agent-hours');        
                // Optionals data
            $idStatut = $this->number('id-client');
            $leadHoursStatut = $this->number('lead-hours');
            $teamHoursStatut = $this->number('team-hours');
            $supportHoursStatut = $this->number('support-hours');
            $videoHoursStatut = $this->number('video-hours');
            $dogHoursStatut = $this->number('dog-hours');
            $priceTTCStatut = $this->number('priceTTC');
            
            // 2) If all obligatory fields are alright => secure and insert into database
            if($agentHoursStatut === 200 && $datesStatut === 200 && $priceTTCStatut === 200){

                // Secure data    
                $agentHours = htmlspecialchars($_POST['agent-hours']);
                $idClient = $this->integer($idStatut , 'id-client');
                $dateBegin = htmlspecialchars($_POST['begin-date']);
                $dateEnd = htmlspecialchars($_POST['end-date']);
                $leadHours = $this->integer($leadHoursStatut , 'lead-hours');      
                $teamHours = $this->integer($teamHoursStatut , 'team-hours');
                $supportHours = $this->integer($supportHoursStatut , 'support-hours');      
                $videoHours = $this->integer($videoHoursStatut , 'video-hours');      
                $dogHours = $this->integer($dogHoursStatut , 'dog-hours');
                $priceTTC = $this->float($priceTTCStatut, 'priceTTC');


                // Verify if agent exist, if not => redirect
                $clientExist = new \Models\Client();
                $clientExist = $clientExist->getData($idClient);
                
                // If client hasn't subscribed, his default identifiant will be 0 (he can order without subscribing if he want)
                if(!$clientExist && $idClient != 0){
                    // User modified the option value in html, he is an admin so i don't disconnect him
                    $idStatut = 8;
                    \Http::redirect("order-$idStatut-$datesStatut-$priceTTCStatut-$agentHoursStatut-$leadHoursStatut-$teamHoursStatut-$supportHoursStatut-$videoHoursStatut-$dogHoursStatut");
                }
                 
                // Price length will not exceed 22 characters (like in database)
                if($priceTTC > 0 && $priceTTC < 10000000000000000000000){

                    $order = new \Models\Order();
                    $order->new($idClient, $leadHours,
                    $teamHours, $supportHours,
                    $videoHours, $agentHours,
                    $dogHours, $dateBegin,
                    $dateEnd, $priceTTC);

                    \Http::redirect('order-success-2');

                }else{
                    $priceTTCStatut = 9;
                    \Http::redirect("order-$idStatut-$datesStatut-$priceTTCStatut-$agentHoursStatut-$leadHoursStatut-$teamHoursStatut-$supportHoursStatut-$videoHoursStatut-$dogHoursStatut");
                }
                
            }else{                      
                \Http::redirect("order-$idStatut-$datesStatut-$priceTTCStatut-$agentHoursStatut-$leadHoursStatut-$teamHoursStatut-$supportHoursStatut-$videoHoursStatut-$dogHoursStatut");
            }
        }
    }

}