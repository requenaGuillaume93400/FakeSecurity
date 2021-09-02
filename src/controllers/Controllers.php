<?php

namespace Controllers;

abstract class Controllers
{

    protected const REGEX_TEXT = "/^[a-zA-z'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}[a-zA-z'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù\- ]*[a-zA-z'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}$/";
    protected const REGEX_ADDRESS = "/^[a-zA-Z0-9'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}[a-zA-Z0-9'\-éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù ]*[a-zA-Z0-9'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}$/";
    protected const REGEX_POSTAL = "/[A-Z(^<>)]{0,2}[0-9]{3,5}/";
    protected const REGEX_SOCIETY = "/^[a-zA-z0-9&'\-éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}[a-zA-z0-9&'\-éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù ]*[a-zA-z0-9&'\-éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}$/";    
    protected const REGEX_MAIL = "/^[a-zA-Z0-9.!#$%&'*+=?^_`{|}~-]+@{1}[a-zA-Z0-9]+[-]{0,}[a-zA-Z0-9]+\.{1}[a-zA-Z]{2,}/";
    protected const REGEX_ADDITIONAL = "/^[^<>]+$/";
    protected const REGEX_PHONE = "/[+0-9]{1,3}[0-9]{9}/";
    protected const REGEX_MATRICULE = "/[A-Z]{4}[0-9]{1,4}[0-9A-Z]{4}$/";
    private const REGEX_DATE = "/[0-9]{4}-{1}[0-9]{2}-{1}[0-9]{2}/";
    private const REGEX_PASSWORD_1 = "/[a-z]{1}/";
    private const REGEX_PASSWORD_2 = "/[A-Z]{1}/";
    private const REGEX_PASSWORD_3 = "/[0-9]{1}/";
    private const REGEX_PASSWORD_4 = "/[.#~+=*\-_+²$=¤]{1}/";

/*************************************************************************************************
 **************************** Demand.php & Subscribe.php & Update.php ****************************
 *************************************************************************************************/
    // Verify a data and return it's statut number code
    protected function data(string $name, string $regex, int $minLength, int $maxLength): int
    {
        if(isset($_POST[$name]) && !empty($_POST[$name])){
            if($this->dataLength($_POST[$name], $minLength, $maxLength)){
                if(preg_match($regex, $_POST[$name])){        
                    return 200; // Success
                }else{        
                    return 3; // Does not respect regex format
                }
            }else{      
                return 2; // Not the correct length
            }
        }else{    
            return 1; // Doesn't exist or is empty
        }
    }


    // Verify optionnal data (like society input)
    protected function optionnalData(string $name, string $regex, int $minLength, int $maxLength): int
    {
        if(isset($_POST[$name]) && !empty($_POST[$name])){
            return $this->data($name, $regex, $minLength, $maxLength);
        }  
        return 1; // Doesn't exist or is empty
    }

    // Verify data length
    private function dataLength(string $data, int $minLength, int $maxLength): bool
    {
        if(strlen($data) > $minLength && strlen($data) < $maxLength){
            return true;
        }
        return false;
    }


/*************************************************************************************************
 ****************************** Demand.php & Order.php & Update.php ******************************
 *************************************************************************************************/
    // Verif input type number 
    protected function number(String $input): int
    {
        if(isset($_POST[$input]) && !empty($_POST[$input])){
            if(intval($_POST[$input]) > 0){
                return 200; // Success
            }
            return 4; // Must be superior to 0
        }
        return 1; // Doesn't exist or is empty
    }   


/*************************************************************************************************
 ************************************* Demand.php & Order.php ************************************
 *************************************************************************************************/
    // Verify if dates are coherent, @return int
    protected function dates(String $date1, String $date2): int
    {
        if(isset($_POST[$date1]) && !empty($_POST[$date1]) && isset($_POST[$date2]) && !empty($_POST[$date2])){
            if($_POST[$date1] < $_POST[$date2]){
                if($this->data($date1, self::REGEX_DATE, 9, 11) === 200 && $this->data($date2, self::REGEX_DATE, 9, 11) === 200){
                    return 200; // Success
                }
                return 7; // There is an error in date (user tried to modify html?)
            }else{
                return 6; // Begin date is superior to end date, nonsense
            }
        }else{
            return 1; // Is empty or doesn't exist
        }
    }


    // Secure integer number
    protected function integer(int $statut, String $input): int
    {
        if($statut === 200){
            return intval($_POST[$input]);
        }
        return 0;
    }


    // Secure number that can be decimals
    protected function float(int $statut, String $input): float
    {
        if($statut === 200){
            return floatval($_POST[$input]);
        }
        return 0;
    }


    // Convert optionnal fields to "" if an error has occured, else secure the data
    protected function optionnal(int $statut, String $input): String
    {
        if($statut === 200){
            $variable = htmlspecialchars($_POST[$input]);
        }else{
            $variable = "";
        }
        return $variable;
    }

/*************************************************************************************************
 ************************************* Demand.php & Update.php ***********************************
 *************************************************************************************************/
    // Verify select name="type" 
    protected function type(array $list, String $select): int
    {
        if(isset($_POST[$select]) && !empty($_POST[$select])){
            if(in_array($_POST[$select], $list)){
                return 200; // Success
            }
            return 5; // User tried to modify html value of select's option
        }
        return 1; // Doesn't exist or is empty
      }


/*************************************************************************************************
 ************************************* Subscribe.php & Update.php ********************************
 *************************************************************************************************/
    // Verify format of the password
    protected function password(string $statut): int
    {
        if(isset($_POST['pass-'.$statut]) && !empty($_POST['pass-'.$statut])){
            if($this->dataLength($_POST['pass-'.$statut], 5, 256)){
                if(preg_match(self::REGEX_PASSWORD_1, $_POST['pass-'.$statut])
                && preg_match(self::REGEX_PASSWORD_2, $_POST['pass-'.$statut])
                && preg_match(self::REGEX_PASSWORD_3, $_POST['pass-'.$statut])
                && preg_match(self::REGEX_PASSWORD_4, $_POST['pass-'.$statut])){        
                    return 200; // Success
                }else{        
                    return 5; // Does not respect regex format
                }
            }else{
                return 2; // Not the correct length
            }
        }else{
            return 1; // Doesn't exist or is empty
        }  
    }


    // Verify if password and retype password matches
    protected function passwordMatches(string $statut): int
    {
        if(isset($_POST['pass-verif-'.$statut]) && !empty($_POST['pass-'.$statut])){
            if($_POST['pass-'.$statut] === $_POST['pass-verif-'.$statut]){        
                return 200; // Success
            }else{
                return 4; // The 2 passwords does not matches
            }
        }
        return 1; // Doesn't exist or is empty
    }

}