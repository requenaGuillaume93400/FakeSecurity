<?php

namespace Controllers;

require_once 'src/autoload.php';

class Demand extends Controllers
{

    // Price in € for 1 hour of work
    private const LEAD_PRICE = 14.74;
    private const TEAM_PRICE = 10.59;
    private const SUPPORT_PRICE = 10.48;
    private const VIDEO_PRICE = 10.4;
    private const AGENT_PRICE = 10.28;
    private const DOG_PRICE = 10.59;
    private const TVA = 1.20;
    private const BENEFICE = 1.12;

    // Run main script
    public function run(): void
    {
        
        session_start();

        if(\Access::connected()){
            $id = $_SESSION['id'];
            $statut = $_SESSION['statut'];

            // Get personnal account informations to complete some fields in template where we already have the infos in database
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
        }else{
            $id = 'undefined';
            $statut = 'undefined';
            $result = [];
        }

        // Errors List
        $dataStatut = [
            200 => '', // Success
            1 => 'Le champ doit être complété',
            2 => 'La valeur est trop longue ou trop courte',
            3 => 'Ne respecte pas le format imposé ou contient des caractères interdits',
            4 => 'Cette valeur doit être supérieur a 0',
            5 => 'Si vous vous amusez à modifier du html, ça ne va pas le faire...',
            6 => 'Il y à une incohérence dans les dates (date de début supérieure à la date de fin)',
            7 => 'Une erreur figure dans les dates'
        ];

        // Get url, turn it into an array, separator - (because of URL rewriting, Notification class couldn't work here because of maximum paramter 9 in url rewriting)
        $getQueryString = explode("-",$_SERVER['REQUEST_URI']);

        // Submit demand (send mail to FakeSecurity and create PDF for client)
        $this->submit();

        $pageTitle = 'Devis';

        \Renderer::render('demand', compact('id', 'statut', 'pageTitle', 'result', 'dataStatut', 'getQueryString'));

    }


    // Submit estimation demand
    private function submit(): void
    {
        if(isset($_POST['submit'])){

            // List of authorized values for <select name='type'>
            $authorizedSiteType = ['alimentaire', 'bricolage', 'culturel', 'bijouterie', 'gare', 'aeroport', 'concert', 'sport', 'autre'];
        
            // 1) Verify if Data are coherent
                // Required data
            $datesStatut = $this->dates('begin-date', 'end-date');
            $lastNameStatut = $this->data('last-name', self::REGEX_TEXT, 2, 50);
            $firstNameStatut = $this->data('first-name', self::REGEX_TEXT, 2, 50);
            $mailStatut = $this->data('mail', self::REGEX_MAIL, 2, 50);
            $phoneStatut = $this->data('phone', self::REGEX_PHONE, 9, 13);
            $siteNameStatut = $this->data('site', self::REGEX_TEXT, 2, 50);
            $siteTypeStatut = $this->type($authorizedSiteType, 'type');
            $agentNumberStatut = $this->number('agent-number');
            $agentDaysStatut = $this->number('agent-days');
            $agentHoursStatut = $this->number('agent-hours');
            $superficieStatut = $this->number('superficie');
            
                // Optionals data
            $societyStatut = $this->optionnalData('society', self::REGEX_SOCIETY, 1, 50);
            $additionalStatut = $this->optionnalData('additionnal', self::REGEX_ADDITIONAL, 2, 1000);
            $leadDaysStatut = $this->number('lead-days');
            $leadHoursStatut = $this->number('lead-hours');
            $teamDaysStatut = $this->number('team-days');
            $teamHoursStatut = $this->number('team-hours');
            $supportNumberStatut = $this->number('support-number');
            $supportDaysStatut = $this->number('support-days');
            $supportHoursStatut = $this->number('support-hours');
            $videoNumberStatut = $this->number('video-number');
            $videoDaysStatut = $this->number('video-days');
            $videoHoursStatut = $this->number('video-hours');
            $dogNumberStatut = $this->number('dog-number');
            $dogDaysStatut = $this->number('dog-days');
            $dogHoursStatut = $this->number('dog-hours');    
    
            // If all obligatory fields are alright => secure data then insert into PDF & Mail
            if($lastNameStatut === 200 
                && $firstNameStatut === 200 
                && $mailStatut === 200 
                && $phoneStatut === 200
                && $agentNumberStatut === 200
                && $agentDaysStatut === 200
                && $agentHoursStatut === 200
                && $superficieStatut === 200
                && $siteTypeStatut === 200
                && $siteNameStatut === 200
                && $datesStatut === 200
            ){

                // 2) Secure data
                    // Required data
                $siteName = htmlspecialchars($_POST['site']);
                $siteType = htmlspecialchars($_POST['type']);
                $dateBegin = htmlspecialchars($_POST['begin-date']);
                $dateEnd = htmlspecialchars($_POST['end-date']);
                $lastName = htmlspecialchars($_POST['last-name']);
                $firstName = htmlspecialchars($_POST['first-name']);
                $mail = htmlspecialchars($_POST['mail']);
                $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);            
                $phone = htmlspecialchars($_POST['phone']);
                $superficie = htmlspecialchars($_POST['superficie']);
                $agentNumber = htmlspecialchars($_POST['agent-number']);
                $agentDays = htmlspecialchars($_POST['agent-days']);
                $agentHours = htmlspecialchars($_POST['agent-hours']);
        
                    // Optionnals data 
                $society = $this->optionnal($societyStatut, 'society');
                $additional = $this->optionnal($additionalStatut, 'additionnal');        
                $leadDays = $this->integer($leadDaysStatut , 'lead-days');
                $leadHours = $this->integer($leadHoursStatut , 'lead-hours');      
                $teamDays = $this->integer($teamDaysStatut , 'team-days');
                $teamHours = $this->integer($teamHoursStatut , 'team-hours');
                $supportNumber = $this->integer($supportNumberStatut , 'support-number');
                $supportDays = $this->integer($supportDaysStatut , 'support-days');
                $supportHours = $this->integer($supportHoursStatut , 'support-hours');      
                $videoNumber = $this->integer($videoNumberStatut , 'video-number');
                $videoDays = $this->integer($videoDaysStatut , 'video-days');
                $videoHours = $this->integer($videoHoursStatut , 'video-hours');      
                $dogNumber = $this->integer($dogNumberStatut , 'dog-number');
                $dogDays = $this->integer($dogDaysStatut , 'dog-days');
                $dogHours = $this->integer($dogHoursStatut , 'dog-hours');
        
                // 3) Calcul Price
                $dogPrice = $dogNumber * $dogDays * $dogHours * self::DOG_PRICE;
                $dogFinalPrice = $dogPrice * self::BENEFICE;
                $agentPrice = $agentNumber * $agentDays * $agentHours * self::AGENT_PRICE;
                $agentFinalPrice = $agentPrice * self::BENEFICE;
                $videoPrice = $videoNumber * $videoDays * $videoHours * self::VIDEO_PRICE;
                $videoFinalPrice = $videoPrice * self::BENEFICE;
                $supportPrice = $supportNumber * $supportDays * $supportHours * self::SUPPORT_PRICE;
                $supportFinalPrice = $supportPrice * self::BENEFICE;
                $teamPrice = $teamDays * $teamHours * self::TEAM_PRICE;
                $teamFinalPrice = $teamPrice * self::BENEFICE;
                $leadPrice = $leadDays * $leadHours * self::LEAD_PRICE;
                $leadFinalPrice = $leadPrice * self::BENEFICE;
        
                $startPrice = $dogPrice + $agentPrice + $videoPrice + $supportPrice + $teamPrice + $leadPrice;
                
                $priceHT = $dogFinalPrice + $agentFinalPrice + $videoFinalPrice + $supportFinalPrice + $teamFinalPrice + $leadFinalPrice;
                $priceTTC = $priceHT * self::TVA;
                $amountTVA = $priceTTC - $priceHT;
                
                // Benefits for us, don't show to client (used in $mail->send())
                $myBenef = $priceHT - $startPrice;
        
                // 4) Generate PDF for the client
                $pdf = new \Pdf();
                $pdf->generate(
                                $priceHT, $amountTVA, $priceTTC, $lastName, $firstName, $society, $phone,
                                $mail, $siteType, $siteName, $superficie, $dateBegin, $dateEnd,
                                $leadDays, $leadHours, $leadFinalPrice, $teamDays, $teamHours, $teamFinalPrice,
                                $supportNumber, $supportDays, $supportHours, $supportFinalPrice,
                                $agentNumber, $agentDays, $agentHours, $agentFinalPrice,
                                $videoNumber, $videoDays, $videoHours, $videoFinalPrice,
                                $dogNumber, $dogDays, $dogHours, $dogFinalPrice
                                );
                                        
                // 5) Send a mail to FakeSecurity Team to notify them
                $email = new \Mail();
                $email->send(
                            $priceHT, $amountTVA, $priceTTC, $myBenef, $lastName, $firstName, $society, $phone,
                            $mail, $siteType, $siteName, $superficie, $additional, $dateBegin, $dateEnd,
                            $leadDays, $leadHours, $leadPrice, $leadFinalPrice,
                            $teamDays, $teamHours, $teamPrice, $teamFinalPrice,
                            $supportNumber, $supportDays, $supportHours, $supportPrice, $supportFinalPrice,
                            $agentNumber, $agentDays, $agentHours, $agentPrice, $agentFinalPrice,
                            $videoNumber, $videoDays, $videoHours, $videoPrice, $videoFinalPrice,
                            $dogNumber, $dogDays, $dogHours, $dogPrice, $dogFinalPrice
                           );
        
            }else{
                \Http::redirect("demand-$siteTypeStatut-$siteNameStatut-$superficieStatut-$additionalStatut-$datesStatut-$leadDaysStatut-$leadHoursStatut-$teamDaysStatut-$teamHoursStatut-$supportNumberStatut-$supportDaysStatut-$supportHoursStatut-$videoNumberStatut-$videoDaysStatut-$videoHoursStatut-$agentNumberStatut-$agentDaysStatut-$agentHoursStatut-$dogNumberStatut-$dogDaysStatut-$dogHoursStatut-$lastNameStatut-$firstNameStatut-$societyStatut-$mailStatut-$phoneStatut");
            }
        
        }
    }

}