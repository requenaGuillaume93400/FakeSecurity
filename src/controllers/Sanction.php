<?php

namespace Controllers;

require_once 'src/autoload.php';

class Sanction extends Controllers
{

    public function run(): void
    {

        session_start();

        // Verify if you have admin rights, if not -> disconnect you and show a message
        \Access::admin();

        $id = $_SESSION['id'];
        $statut = $_SESSION['statut'];

        if(isset($_GET['success']) && intval($_GET['success']) === 2){
            $notifSuccess = "L'agent a bien été sanctionné.";
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
            8 => 'L\'agent n\'existe pas, vous jouer à quoi là ?'
        ];

        // Errors notifications (GET)
        $notif = new \Notification();
        $notifR = $notif->setNotif('reason');
        $notifIa = $notif->setNotif('agent');
        $notifIs = $notif->setNotif('sanction');
        $notifD = $notif->setNotif('date');

        // Get list of agent for select id-agent
        $agent = new \Models\Agent();
        $agents = $agent->getAll();

        // Get list of sanction for select id-sanction
        $sanction = new \Models\SanctionList();
        $sanctions = $sanction->getAll();
        
        $this->add();

        $pageTitle = 'Nouvelle commande';

        \Renderer::render('sanction', compact(
                                              'id', 'statut', 'pageTitle', 'agents', 'sanctions', 'notifSuccess',
                                              'dataStatut', 'notifR', 'notifIa', 'notifIs', 'notifD'
                                             )
                         );

    }


    // Add a new sanction for an agent
    private function add()
    {
        if(isset($_POST['submit'])){

            // 1) Verify data
            $idAgentStatut = $this->number('id-agent');
            $idSanctionStatut = $this->number('id-sanction');
            $reasonStatut = $this->data('reason', self::REGEX_TEXT, 2, 256);
            $datesStatut = $this->dates('begin-date', 'end-date');

            // 2) If all obligatory fields are alright => secure and insert into database               
            if($datesStatut === 200 && $idAgentStatut === 200 && $idSanctionStatut === 200 && $reasonStatut === 200){

                // There is actually 7 sanctions (1 to 7), accept only a number between those 2 values (include)
                if($_POST['id-sanction'] > 0 && $_POST['id-sanction'] < 8){

                    // Secure data
                    $idAgent = intval($_POST['id-agent']);

                    // Verify if agent exist, if not => redirect, no need to go further in the script
                    $agentExist = new \Models\Agent();
                    $agentExist = $agentExist->getData($idAgent);
                    if(!$agentExist){
                        // User modified the option value in html, he is an admin so i don't disconnect him
                        $idAgentStatut = 8;
                        \Http::redirect("sanction-$idAgentStatut-$idSanctionStatut-$reasonStatut-$datesStatut");
                    }

                    $idSanction = intval($_POST['id-sanction']);
                    $reason = htmlspecialchars($_POST['reason']);
                    $dateSummon = htmlspecialchars($_POST['begin-date']);
                    $dateNotification = htmlspecialchars($_POST['end-date']);
    
                    // Insert a new sanction for a selected agent
                    $add = new \Models\Sanction();
                    $add->new($idAgent, $idSanction, $reason, $dateSummon, $dateNotification);
    
                    \Http::redirect('sanction-success-2');

                }else{
                    // User modified the option value in html, he is an admin so i don't disconnect him
                    $idSanctionStatut = 5;
                    \Http::redirect("sanction-$idAgentStatut-$idSanctionStatut-$reasonStatut-$datesStatut");
                }

            }else{
                \Http::redirect("sanction-$idAgentStatut-$idSanctionStatut-$reasonStatut-$datesStatut");
            }
            
        }
    }

}