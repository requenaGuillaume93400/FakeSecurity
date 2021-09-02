<?php

class Mail
{

    // Send mail to FakeSecurity when an user submit a demand
    public function send(
                        float $priceHT, float $amountTVA, float $priceTTC, float $myBenef,
                        String $lastName, String $firstName, String $society, String $phone,
                        String $mail, String $siteType, String $siteName, int $superficie,
                        String $additional, String $dateBegin, String $dateEnd,
                        int $leadDays, int $leadHours, float $leadPrice, float $leadFinalPrice,
                        int $teamDays, int $teamHours, float $teamPrice, float $teamFinalPrice,
                        int $supportNumber, int $supportDays, int $supportHours, 
                        float $supportPrice, float $supportFinalPrice,
                        int $agentNumber, int $agentDays, int $agentHours, 
                        float $agentPrice, float $agentFinalPrice,
                        int $videoNumber, int $videoDays, int $videoHours, 
                        float $videoPrice, float $videoFinalPrice,
                        int $dogNumber, int $dogDays, int $dogHours, 
                        float $dogPrice, float $dogFinalPrice
                      ): void
    {

        $header = "MIME-Version: 1.0\r\n";
        $header .= 'From: "Contact FakeSecurity"<support@fakesecurity.com>'. "\n"; 
        $header .= "Content-Type:text/html; charset='utf-8'" ."\n";
        $header .= "Content-Transfer-Encoding: 8bit";

        ob_start();
        require_once 'src/templates/mail-content.phtml';
        $message = ob_get_contents();
        ob_end_clean();

        mail("guillaume.requena@3wa.io", "Devis FakeSecurity : ".$lastName, $message, $header);
    }

}