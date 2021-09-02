<?php

require_once './src/libraries/dompdf/autoload.inc.php';
require_once 'src/Session.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{

    // Generate recap Pdf for user (for his demand of quotation)
    public function generate(float $priceHT, float $amountTVA, float $priceTTC,
                             String $lastName, String $firstName, String $society, String $phone,
                             String $mail, String $siteType, String $siteName, int $superficie,
                             String $dateBegin, String $dateEnd,
                             int $leadDays, int $leadHours, float $leadFinalPrice,
                             int $teamDays, int $teamHours, float $teamFinalPrice,
                             int $supportNumber, int $supportDays, int $supportHours, float $supportFinalPrice,
                             int $agentNumber, int $agentDays, int $agentHours, float $agentFinalPrice,
                             int $videoNumber, int $videoDays, int $videoHours, float $videoFinalPrice,
                             int $dogNumber, int $dogDays, int $dogHours, float $dogFinalPrice
                            ): void
    {

        ob_start();
        require_once 'src/templates/pdf-content.phtml';
        $html = ob_get_contents();
        ob_end_clean();
        
        $options = new Options();
        $options->set('defaultFont', 'Courrier');
        
        // Instantiate and use the dompdf class
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'landscape' or 'portrait' second argument
        $dompdf->setPaper('A4');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        // Set document title
        $title = 'Demande-devis-FakeSecurity.pdf';
        
        // Output the generated PDF to Browser
        $dompdf->stream($title);

    }

}